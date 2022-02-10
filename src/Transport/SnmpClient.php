<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

interface SnmpClient
{
    /**
     * @param list<string> $oids
     *
     * @return array<string, mixed>
     */
    public function get(array $oids): array;

    /**
     * @param list<string> $oids
     *
     * @return array<string, mixed>
     */
    public function getNext(array $oids): array;

    /** @return array<string, mixed> */
    public function walk(string $oid, int $maxRepetitions = 20): array;

    /**
     * @param array<T, Request> $requests
     *
     * @return array<T, array<string, mixed>>
     *
     * @template T
     */
    public function batch(array $requests): array;
}
