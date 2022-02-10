<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use Throwable;

use function Safe\preg_match;

final class NoSuchObjectExists extends RequestException
{
    public static function fromOid(string $host, string $oid): self
    {
        $self       = self::new();
        $self->host = $host;
        $self->oids = $oid;

        return $self;
    }

    public static function fromThrowable(string $host, Throwable $throwable): self
    {
        $self       = self::new($throwable);
        $self->host = $host;

        if (preg_match("~Error in packet at '(.+?)':~", $throwable->getMessage(), $matches) !== 1) {
            return $self;
        }

        $self->oids = $matches[1];

        return $self;
    }

    private static function new(?Throwable $previous = null): self
    {
        return new self('No Such Object available on this agent at this OID', 0, $previous);
    }
}
