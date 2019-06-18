<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

interface Snmp
{
    /**
     * Strips $oid prefix from the result keys
     *
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable;

    /**
     * Returns complete OID in the result keys
     *
     * @return iterable<string, mixed>
     */
    public function walkWithCompleteOids(string $oid) : iterable;
}
