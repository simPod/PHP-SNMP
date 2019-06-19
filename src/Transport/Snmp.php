<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

interface Snmp
{
    /**
     * @return iterable<string, mixed> Key is OID without $oid prefix
     */
    public function walk(string $oid) : iterable;

    /**
     * @return iterable<string, mixed> Key is complete OID
     */
    public function walkWithCompleteOids(string $oid) : iterable;
}
