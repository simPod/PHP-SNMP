<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\EndOfMibReached;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\InvalidVersionProvided;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Exception\NoSuchObjectExists;
use SimPod\PhpSnmp\Exception\SnmpException;
use SimPod\PhpSnmp\Exception\TimeoutReached;
use SNMP;
use SNMPException as NativeSnmpException;
use stdClass;
use Throwable;

use function extension_loaded;
use function implode;
use function is_array;
use function is_int;
use function is_string;
use function reset;
use function sprintf;
use function strpos;

use const SNMP_NULL;
use const SNMP_OID_OUTPUT_NUMERIC;
use const SNMP_VALUE_OBJECT;
use const SNMP_VALUE_PLAIN;

final class ExtensionSnmpClient implements SnmpClient
{
    use SimpleBatch;

    private SNMP $snmp;

    /**
     * The `$timeoutMs` parameter is in microseconds, as required by ext-snmp.
     *
     * @param int<0, max> $timeoutMs
     * @param int<0, max> $retry
     */
    public function __construct(
        private string $host = '127.0.0.1',
        string $community = 'public',
        int $timeoutMs = 1000000,
        int $retry = 3,
        string $version = '2c',
        string $secLevel = 'noAuthNoPriv',
        string $authProtocol = 'MD5',
        string $authPassphrase = 'None',
        string $privProtocol = 'DES',
        string $privPassphrase = 'None',
        private int $defaultMaxRepetitions = 20,
        private bool $typedValues = false,
    ) {
        if (! extension_loaded('snmp')) {
            throw GeneralException::new('The SNMP extension is required', host: $host);
        }

        switch ($version) {
            case '1':
                $snmpVersion = SNMP::VERSION_1;

                break;
            case '2c':
                $snmpVersion = SNMP::VERSION_2c;

                break;
            case '3':
                $snmpVersion = SNMP::VERSION_3;

                break;
            default:
                throw InvalidVersionProvided::new($version);
        }

        if ($community === '') {
            throw GeneralException::new('Community string must not be empty');
        }

        $this->snmp = new SNMP($snmpVersion, $host, $community, $timeoutMs, $retry);
        $this->snmp->oid_output_format = SNMP_OID_OUTPUT_NUMERIC;
        $this->snmp->exceptions_enabled = SNMP::ERRNO_ANY;

        if ($typedValues) {
            $this->snmp->valueretrieval = SNMP_VALUE_PLAIN | SNMP_VALUE_OBJECT;
        }

        if ($snmpVersion !== SNMP::VERSION_3) {
            return;
        }

        $this->snmp->setSecurity(
            self::normalizeSecurityLevel($secLevel),
            self::normalizeAuthProtocol($authProtocol),
            $authPassphrase,
            self::normalizePrivacyProtocol($privProtocol),
            $privPassphrase,
        );
    }

    /** @inheritDoc */
    public function get(array $oids): array
    {
        try {
            $output = $this->snmp->get($oids);
        } catch (Throwable $throwable) {
            throw $this->processException($throwable, $oids);
        }

        return $this->processOutput($output, $oids);
    }

    /** @inheritDoc */
    public function getNext(array $oids): array
    {
        try {
            $output = $this->snmp->getnext($oids);
        } catch (Throwable $throwable) {
            throw $this->processException($throwable, $oids);
        }

        return $this->processOutput($output, $oids);
    }

    /** @inheritDoc */
    public function walk(string $oid, int|null $maxRepetitions = null): array
    {
        $maxRepetitions ??= $this->defaultMaxRepetitions;

        if ($maxRepetitions < 0) {
            throw GeneralException::new('Max repetitions must not be negative');
        }

        try {
            $output = $this->snmp->walk($oid, false, $maxRepetitions);
        } catch (Throwable $throwable) {
            $exception = $this->processException($throwable, [$oid]);
            if (
                $this->typedValues
                && ($exception instanceof NoSuchObjectExists || $exception instanceof NoSuchInstanceExists)
            ) {
                throw $this->classifyEmptyWalk($oid);
            }

            throw $exception;
        }

        return $this->processOutput($output, [$oid]);
    }

    private function classifyEmptyWalk(string $oid): SnmpException
    {
        // Net-SNMP can fail a GET on the root of an empty subtree. GETNEXT can still find a successor.
        $next = $this->getNext([$oid]);
        $value = reset($next);
        if ($value === false) {
            return NoSuchObjectExists::fromOid($this->host, $oid);
        }

        return $value instanceof SnmpValue && $value->type === SNMP_NULL
            ? EndOfMibReached::fromOid($this->host, $oid)
            : NoSuchInstanceExists::fromOid($this->host, $oid);
    }

    /**
     * @param list<string> $oids
     *
     * @return array<string, int|string|SnmpValue>
     */
    private function processOutput(mixed $output, array $oids): array
    {
        if (! is_array($output)) {
            throw GeneralException::new('Unexpected empty response from SNMP extension', null, $this->host, $oids);
        }

        $result = [];
        foreach ($output as $oid => $value) {
            if (! is_string($oid)) {
                throw GeneralException::new('Unexpected output from SNMP extension', null, $this->host, $oids);
            }

            if (! $this->typedValues && is_string($value)) {
                $result[$oid] = ValueParser::parse($value);

                continue;
            }

            if ($this->typedValues && $value instanceof stdClass) {
                $type = $value->type ?? null;
                $payload = $value->value ?? null;
                if (is_int($type) && (is_string($payload) || ($type === SNMP_NULL && $payload === null))) {
                    $result[$oid] = new SnmpValue($type, $payload);

                    continue;
                }
            }

            throw GeneralException::new('Unexpected output from SNMP extension', null, $this->host, $oids);
        }

        return $result;
    }

    /** @return 'authNoPriv'|'authPriv'|'noAuthNoPriv' */
    private static function normalizeSecurityLevel(string $secLevel): string
    {
        return match ($secLevel) {
            'authNoPriv', 'authPriv', 'noAuthNoPriv' => $secLevel,
            default => throw GeneralException::new(sprintf('Invalid security level "%s"', $secLevel)),
        };
    }

    /** @return 'MD5'|'SHA'|'SHA256'|'SHA512' */
    private static function normalizeAuthProtocol(string $authProtocol): string
    {
        return match ($authProtocol) {
            'MD5', 'SHA', 'SHA256', 'SHA512' => $authProtocol,
            default => throw GeneralException::new(sprintf('Invalid auth protocol "%s"', $authProtocol)),
        };
    }

    /** @return 'AES'|'AES128'|'DES' */
    private static function normalizePrivacyProtocol(string $privProtocol): string
    {
        return match ($privProtocol) {
            'AES', 'AES128', 'DES' => $privProtocol,
            default => throw GeneralException::new(sprintf('Invalid privacy protocol "%s"', $privProtocol)),
        };
    }

    /** @param list<string> $oids */
    private function processException(Throwable $throwable, array $oids): Throwable
    {
        if ($throwable instanceof NativeSnmpException && $throwable->getCode() === SNMP::ERRNO_TIMEOUT) {
            return TimeoutReached::fromOid($this->host, implode(', ', $oids));
        }

        if (strpos($throwable->getMessage(), 'No Such Object') !== false) {
            return NoSuchObjectExists::fromThrowable($this->host, $throwable);
        }

        if (
            strpos($throwable->getMessage(), 'No Such Instance') !== false
            || strpos($throwable->getMessage(), 'noSuchName') !== false
        ) {
            return NoSuchInstanceExists::fromThrowable($this->host, $throwable);
        }

        if (strpos($throwable->getMessage(), 'No more variables left in this MIB View') !== false) {
            return EndOfMibReached::fromThrowable($this->host, $throwable);
        }

        if (strpos($throwable->getMessage(), 'No response') !== false) {
            return TimeoutReached::fromOid($this->host, implode(', ', $oids));
        }

        return GeneralException::fromThrowable($throwable, $this->host, $oids);
    }
}
