<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use Throwable;

use function Safe\preg_match;

final class EndOfMibReached extends RequestException
{
    public static function new(?Throwable $previous = null): self
    {
        return new self('No more variables left in this MIB View (It is past the end of the MIB tree)', 0, $previous);
    }

    public static function fromOid(string $host, string $oid): self
    {
        $self = self::new();
        $self->host = $host;
        $self->oids = $oid;

        return $self;
    }

    public static function fromThrowable(string $host, Throwable $throwable): self
    {
        $self = self::new();
        $self->host = $host;

        if (preg_match("~Error in packet at '(.+?)':~", $throwable->getMessage(), $matches) !== 1) {
            return $self;
        }

        $self->oids = $matches[1];

        return $self;
    }
}
