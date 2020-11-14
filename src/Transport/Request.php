<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

final class Request
{
    public const GET      = 'get';
    public const GET_NEXT = 'getNext';
    public const WALK     = 'walk';

    public string $type;

    /** @var list<string> */
    public $oids;

    public ?int $maxRepetitions = null;

    /** @param list<string> $oids */
    private function __construct(string $type, array $oids, ?int $maxRepetitions = null)
    {
        $this->type           = $type;
        $this->oids           = $oids;
        $this->maxRepetitions = $maxRepetitions;
    }

    /** @param list<string> $oids */
    public static function get(array $oids) : self
    {
        return new self(self::GET, $oids);
    }

    /** @param list<string> $oids */
    public static function getNext(array $oids) : self
    {
        return new self(self::GET_NEXT, $oids);
    }

    public static function walk(string $oid, int $maxRepetitions = 20) : self
    {
        return new self(self::WALK, [$oid], $maxRepetitions);
    }
}
