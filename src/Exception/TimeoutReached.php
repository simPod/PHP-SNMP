<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

final class TimeoutReached extends RequestException
{
    public static function fromOid(string $host, string $oid): self
    {
        $self       = new self('Request timeout');
        $self->host = $host;
        $self->oids = $oid;

        return $self;
    }
}
