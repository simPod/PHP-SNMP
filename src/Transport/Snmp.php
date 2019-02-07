<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

interface Snmp
{
    /**
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable;

    /**
     * @return iterable<string, mixed>
     */
    public function walkFirstDegree(string $oid) : iterable;
}
