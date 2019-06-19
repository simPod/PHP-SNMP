<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\SnmpExtensionError;
use function error_get_last;
use function explode;
use function snmp2_real_walk;
use function snmp3_real_walk;
use function snmp_set_oid_output_format;
use function snmprealwalk;
use function strlen;
use function strpos;
use function strrpos;
use function substr;
use function trim;
use const SNMP_OID_OUTPUT_NUMERIC;

final class ExtensionSnmp implements Snmp
{
    /** @var string */
    private $community;

    /** @var string */
    private $host;

    /** @var string */
    private $version;

    /** @var string */
    private $secName;

    /** @var string */
    private $secLevel;

    /** @var string */
    private $authProtocol;

    /** @var string */
    private $authPassphrase;

    /** @var string */
    private $privProtocol;

    /** @var string */
    private $privPassphrase;

    /** @var int */
    private $retry;

    /** @var int */
    private $timeout;

    public function __construct(
        string $host = '127.0.0.1',
        string $community = 'public',
        int $timeout = 1000000,
        int $retry = 5,
        string $version = '2c',
        string $secLevel = 'noAuthNoPriv',
        string $authProtocol = 'MD5',
        string $authPassphrase = 'None',
        string $privProtocol = 'DES',
        string $privPassphrase = 'None'
    ) {
        $this->community = $community;
        $this->host      = $host;
        $this->retry     = $retry;
        $this->timeout   = $timeout;
        $this->version   = $version;

        $this->secName        = $community;
        $this->secLevel       = $secLevel;
        $this->authProtocol   = $authProtocol;
        $this->authPassphrase = $authPassphrase;
        $this->privProtocol   = $privProtocol;
        $this->privPassphrase = $privPassphrase;
        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable
    {
        $oidLength = strlen($oid) + 1;
        foreach ($this->realWalk($oid) as $oidKey => $value) {
            yield substr($oidKey, $oidLength) => $this->parseSnmpValue($value);
        }
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walkWithCompleteOids(string $oid) : iterable
    {
        foreach ($this->realWalk($oid) as $oidKey => $value) {
            yield $oidKey => $this->parseSnmpValue($value);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function realWalk(string $oid) : array
    {
        switch ($this->version) {
            case '1':
                $result = @snmprealwalk(
                    $this->host,
                    $this->community,
                    $oid,
                    $this->timeout,
                    $this->retry
                );
                break;
            case '2c':
                $result = @snmp2_real_walk(
                    $this->host,
                    $this->community,
                    $oid,
                    $this->timeout,
                    $this->retry
                );
                break;
            case '3':
                $result = @snmp3_real_walk(
                    $this->host,
                    $this->secName,
                    $this->secLevel,
                    $this->authProtocol,
                    $this->authPassphrase,
                    $this->privProtocol,
                    $this->privPassphrase,
                    $oid,
                    $this->timeout,
                    $this->retry
                );
                break;
            default:
                throw SnmpExtensionError::invalidVersion($this->version);
        }

        if ($result === false) {
            /** @var array $error */
            $error = error_get_last();
            if (strpos($error['message'], 'No more variables left in this MIB') !== false) {
                throw SnmpExtensionError::oidOutOfRange($oid);
            }

            throw SnmpExtensionError::generic($oid, $error['message']);
        }

        return $result;
    }

    /**
     * @return mixed
     */
    private function parseSnmpValue(string $rawValue)
    {
        [$type, $value] = explode(':', $rawValue, 2);

        $value = trim($value);

        switch ($type) {
            case 'Counter64':
            case 'Hex-STRING':
            case 'IpAddress':
            case 'OID':
                return $value;
            case 'STRING':
                return strpos($value, '"') === 0 ? trim(substr(substr($value, 1), 0, -1)) : $value;
            case 'INTEGER':
            case 'Counter32':
            case 'Gauge32':
                return (int) $value;
            case 'Timeticks':
                return (int) substr($value, 1, (int) strrpos($value, ')') - 1);
        }

        throw SnmpExtensionError::unknownType($type);
    }
}
