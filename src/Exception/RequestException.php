<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;

abstract class RequestException extends RuntimeException implements SnmpException
{
    public string $host;

    public ?string $oids = null;
}
