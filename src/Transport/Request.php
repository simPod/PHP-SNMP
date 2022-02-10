<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

final class Request
{
    public const GET = 'get';
    public const GET_NEXT = 'getNext';
    public const WALK = 'walk';

    /** @var list<string> */
    public $oids;

    /** @param list<string> $oids */
    private function __construct(public string $type, array $oids, public int|null $maxRepetitions = null)
    {
        $this->oids = $oids;
    }

    /** @param list<string> $oids */
    public static function get(array $oids): self
    {
        return new self(self::GET, $oids);
    }

    /** @param list<string> $oids */
    public static function getNext(array $oids): self
    {
        return new self(self::GET_NEXT, $oids);
    }

    public static function walk(string $oid, int $maxRepetitions = 20): self
    {
        return new self(self::WALK, [$oid], $maxRepetitions);
    }
}
