<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

/** A varbind value returned by the PHP SNMP extension in plain mode. */
final readonly class SnmpValue
{
    /**
     * @param int $type  ASN.1 type tag
     * @param string|null $value Raw octets for octet strings, decimal text for integer types, or null for ASN.1 NULL.
     */
    public function __construct(public int $type, public string|null $value)
    {
    }
}
