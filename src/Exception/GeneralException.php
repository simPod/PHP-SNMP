<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;
use Throwable;

use function implode;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
final class GeneralException extends RuntimeException implements SnmpException
{
    public string|null $host = null;

    public string|null $oids = null;

    /** @param list<string> $oids */
    public static function new(
        string $error,
        Throwable|null $previous = null,
        string|null $host = null,
        array|null $oids = null,
    ): self {
        $self = new self($error, 0, $previous);
        $self->host = $host;
        if ($oids !== null) {
            $self->oids = implode(', ', $oids);
        }

        return $self;
    }

    /** @param list<string> $oids */
    public static function fromThrowable(Throwable $throwable, string|null $host = null, array|null $oids = null): self
    {
        return self::new($throwable->getMessage(), $throwable, $host, $oids);
    }
}
