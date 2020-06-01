<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;

final class NoRequestsProvided extends RuntimeException implements SnmpException
{
    public static function new() : self
    {
        return new self('You must provide at least one Request to the batch method');
    }
}
