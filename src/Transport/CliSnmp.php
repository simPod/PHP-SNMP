<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\SnmpCliError;
use SimPod\PhpSnmp\Transport\Cli\SnmpBulkWalk;
use function count;
use function error_get_last;
use function in_array;
use function preg_match_all;
use function strlen;
use function substr;
use const PREG_SET_ORDER;

final class CliSnmp implements Snmp
{
    private const REGEX = '~^((?:\.\d+)+) = (""|\d+|([^:]+): (?:"(.*?)"|(.+?)))$~ms';

    /** @var SnmpBulkWalk */
    private $snmpBulkWalk;

    public function __construct(
        string $host = '127.0.0.1',
        string $community = 'public',
        int $timeout = 1,
        int $retries = 5,
        int $commandTimeout = 120,
        string $version = '2c',
        ?SnmpBulkWalk $snmpBulkWalk = null
    ) {
        if (! in_array($version, ['1', '2c'], true)) {
            throw SnmpCliError::invalidVersion($version);
        }

        $this->snmpBulkWalk = $snmpBulkWalk ?? new SnmpBulkWalk(
            $community,
            $host,
            $timeout,
            $retries,
            $commandTimeout,
            $version
        );
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable
    {
        $oidLength = strlen($oid) + 1;
        foreach ($this->realWalk($oid) as $oidKey => $value) {
            yield substr($oidKey, $oidLength) => $value;
        }
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walkWithCompleteOids(string $oid) : iterable
    {
        yield from $this->realWalk($oid);
    }

    /**
     * @return iterable<string, mixed>
     */
    private function realWalk(string $oid) : iterable
    {
        $output = $this->snmpBulkWalk->execute($oid);

        if (preg_match_all(self::REGEX, $output, $matches, PREG_SET_ORDER) === false) {
            throw SnmpCliError::failedToParseOutput($oid, error_get_last()['message'] ?? 'unknown');
        }

        foreach ($matches as $match) {
            if ($match[2] === '""') {
                yield $match[1] => '';
                continue;
            }

            if (count($match) === 3) {
                yield $match[1] => (int) $match[2]; // Timeticks as raw integer
                continue;
            }

            switch ($match[3]) {
                case 'Counter64':
                case 'Hex-STRING':
                case 'IpAddress':
                case 'OID':
                    yield $match[1] => $match[5];
                    break;
                case 'STRING':
                    yield $match[1] => $match[4] ?? $match[5];
                    break;
                case 'INTEGER':
                case 'Counter32':
                case 'Gauge32':
                    yield $match[1] => (int) $match[5];
                    break;
                default:
                    throw SnmpCliError::unknownType($match[3]);
            }
        }
    }
}
