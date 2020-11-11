<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests;

use PHPUnit\Framework\TestCase;
use Throwable;

use function array_intersect_key;
use function get_object_vars;

abstract class BaseTestCase extends TestCase
{
    protected static function assertSnmpException(Throwable $expected, callable $callback) : void
    {
        try {
            $callback();
            self::fail('expected exception to be thrown');
        } catch (Throwable $throwable) {
            self::assertSame($expected->getMessage(), $throwable->getMessage());
            self::assertSame(self::getExceptionProperties($expected), self::getExceptionProperties($throwable));
        }
    }

    /** @return array<string, string|null> */
    private static function getExceptionProperties(Throwable $throwable) : array
    {
        return array_intersect_key(get_object_vars($throwable), ['host' => null, 'oids' => null]);
    }
}
