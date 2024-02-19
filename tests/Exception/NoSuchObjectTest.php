<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Exception;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\NoSuchObjectExists;

#[CoversClass(NoSuchObjectExists::class)]
final class NoSuchObjectTest extends TestCase
{
    public function testFromThrowable(): void
    {
        $exception = new Exception("Error in packet at '.1.4': No Such Object available on this agent at this OID");

        $exception = NoSuchObjectExists::fromThrowable('127.0.0.1', $exception);
        self::assertSame('127.0.0.1', $exception->host);
        self::assertSame('.1.4', $exception->oids);
    }

    public function testFromThrowableWithUnexpectedMessage(): void
    {
        $exception = new Exception('unexpected message');

        $exception = NoSuchObjectExists::fromThrowable('127.0.0.1', $exception);
        self::assertSame('127.0.0.1', $exception->host);
        self::assertNull($exception->oids);
    }
}
