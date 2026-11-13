<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

final class Request
{
    public const Get = 'get';
    public const GetNext = 'getNext';
    public const Walk = 'walk';

    /** @param list<string> $oids */
    private function __construct(
        public string $type,
        public array $oids,
        public int|null $maxRepetitions = null,
    ) {
    }

    /** @param list<string> $oids */
    public static function get(array $oids): self
    {
        return new self(self::Get, $oids);
    }

    /** @param list<string> $oids */
    public static function getNext(array $oids): self
    {
        return new self(self::GetNext, $oids);
    }

    /** Omit max repetitions to use the executing client's default. */
    public static function walk(string $oid, int|null $maxRepetitions = null): self
    {
        return new self(self::Walk, [$oid], $maxRepetitions);
    }
}
