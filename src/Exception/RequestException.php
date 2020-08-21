<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;

abstract class RequestException extends RuntimeException implements SnmpException
{
    /** @var string */
    public $host;

    /** @var string|null */
    public $oids;
}
