<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Exception;

use Exception;
use SimPod\PhpSnmp\Exception\EndOfMibReached;
use SimPod\PhpSnmp\Tests\BaseTestCase;

final class EndOfMibTest extends BaseTestCase
{
    public function testFromThrowable(): void
    {
        $exception = new Exception(
            "Error in packet at '.1.4': No more variables left in this MIB View (It is past the end of the MIB tree)"
        );

        $exception = EndOfMibReached::fromThrowable('127.0.0.1', $exception);
        self::assertSame('127.0.0.1', $exception->host);
        self::assertSame('.1.4', $exception->oids);
    }

    public function testFromThrowableWithUnexpectedMessage(): void
    {
        $exception = new Exception('unexpected message');

        $exception = EndOfMibReached::fromThrowable('127.0.0.1', $exception);
        self::assertSame('127.0.0.1', $exception->host);
        self::assertNull($exception->oids);
    }
}
