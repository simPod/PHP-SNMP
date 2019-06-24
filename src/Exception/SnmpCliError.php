<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;
use SimPod\PhpSnmp\SnmpException;
use function sprintf;

final class SnmpCliError extends RuntimeException implements SnmpException
{
    public static function invalidVersion(string $version) : self
    {
        return new self(sprintf('Invalid SNMP version "%s"', $version));
    }

    public static function unknownType(string $type) : self
    {
        return new self(sprintf('Encountered unknown type "%s"', $type));
    }

    public static function generic(string $oid, string $error) : self
    {
        return new self(sprintf('Could not perform walk for OID "%s": %s', $oid, $error));
    }

    public static function failedToParseOutput(string $oid, string $error) : self
    {
        return new self(sprintf('Failed to parse SNMP output for OID "%s": %s', $oid, $error));
    }
}
