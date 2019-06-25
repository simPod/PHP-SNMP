<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;
use SimPod\PhpSnmp\SnmpException;
use function sprintf;

final class SnmpExtensionError extends RuntimeException implements SnmpException
{
    public static function invalidVersion(string $version) : self
    {
        return new self(sprintf('Invalid SNMP version "%s"', $version));
    }

    public static function oidOutOfRange(string $oid) : self
    {
        return new self(sprintf('OID "%s" is out of the MIB tree range (does not exist)', $oid));
    }

    public static function unknownType(string $type) : self
    {
        return new self(sprintf('Encountered unknown type "%s"', $type));
    }

    public static function generic(string $oid, string $error) : self
    {
        return new self(sprintf('Could not perform walk for OID "%s": %s', $oid, $error));
    }
}
