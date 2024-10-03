<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Transport;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\NoRequestsProvided;
use SimPod\PhpSnmp\Transport\Request;
use SimPod\PhpSnmp\Transport\SimpleBatch;
use SimPod\PhpSnmp\Transport\SnmpClient;

#[CoversTrait(SimpleBatch::class)]
final class SimpleBatchTest extends TestCase
{
    /**
     * @param list<Request> $requests
     * @param list<array<string, mixed>> $result
     */
    #[DataProvider('providerBatch')]
    public function testBatch(callable $snmpClientFactory, array $requests, array $result): void
    {
        $batchSnmpClient = $this->createBatchSnmpClient($snmpClientFactory($this));

        self::assertSame($result, $batchSnmpClient->batch($requests));
    }

    /** @return iterable<mixed> */
    public static function providerBatch(): iterable
    {
        $snmpClientFactory = static function (TestCase $testCase) {
            $snmpClient = $testCase->createMock(SnmpClient::class);
            $snmpClient->expects($testCase->once())->method('get')->with(['.1.2.3'])->willReturn(['.1.2.3' => 123]);

            return $snmpClient;
        };

        yield 'single get' => [$snmpClientFactory, [Request::get(['.1.2.3'])], [['.1.2.3' => 123]]];

        $snmpClientFactory = static function (TestCase $testCase) {
            $snmpClient = $testCase->createMock(SnmpClient::class);
            $snmpClient
                ->expects($testCase->once())
                ->method('getNext')
                ->with(['.1.2.3'])
                ->willReturn(['.1.2.3.1' => 1231]);

            return $snmpClient;
        };

        yield 'single getNext' => [$snmpClientFactory, [Request::getNext(['.1.2.3'])], [['.1.2.3.1' => 1231]]];

        $snmpClientFactory = static function (TestCase $testCase) {
            $snmpClient = $testCase->createMock(SnmpClient::class);
            $snmpClient
                ->expects($testCase->once())->method('walk')
                ->with('.1.2.3', 10)
                ->willReturn(['.1.2.3.4.5' => 12345]);

            return $snmpClient;
        };

        yield 'single walk' => [$snmpClientFactory, [Request::walk('.1.2.3', 10)], [['.1.2.3.4.5' => 12345]]];

        $snmpClientFactory = static function (TestCase $testCase) {
            $snmpClient = $testCase->createMock(SnmpClient::class);
            $snmpClient
            ->expects($testCase->once())
            ->id('get')
            ->method('get')
            ->with(['.1.2.3', '.4.5.6'])
            ->willReturn(['.1.2.3' => 123, '.4.5.6' => 456]);

            $snmpClient
            ->expects($testCase->exactly(2))
            ->id('walk')
            ->method('walk')
            ->after('get')
            ->willReturnMap(
                [
                    ['.1.2.3', 10, ['.1.2.3.4.5' => 12345]],
                    ['.3.2.1', 10,['.3.2.1.1.2' => 32112]],
                ],
            );

            $snmpClient
            ->expects($testCase->once())
            ->after('walk')
            ->method('getNext')
            ->with(['.7.8.9'])
            ->willReturn(['.7.8.9.1' => 7891]);

            return $snmpClient;
        };

        yield 'multiple requests' => [
            $snmpClientFactory,
            [
                'get' => Request::get(['.1.2.3', '.4.5.6']),
                'walk' => Request::walk('.1.2.3', 10),
                'getNext' => Request::getNext(['.7.8.9']),
                'anotherWalk' => Request::walk('.3.2.1', 10),
            ],
            [
                'get' => ['.1.2.3' => 123, '.4.5.6' => 456],
                'walk' => ['.1.2.3.4.5' => 12345],
                'getNext' => ['.7.8.9.1' => 7891],
                'anotherWalk' => ['.3.2.1.1.2' => 32112],
            ],
        ];
    }

    public function testBatchNoRequests(): void
    {
        $this->expectExceptionObject(NoRequestsProvided::new());

        $batchSnmpClient = $this->createBatchSnmpClient($this->createMock(SnmpClient::class));
        $batchSnmpClient->batch([]);
    }

    private function createBatchSnmpClient(SnmpClient $snmpClient): SnmpClient
    {
        return new class ($snmpClient) implements SnmpClient {
            use SimpleBatch;

            public function __construct(private readonly SnmpClient $snmpClient)
            {
            }

            /** @inheritDoc */
            public function get(array $oids): array
            {
                return $this->snmpClient->get($oids);
            }

            /** @inheritDoc */
            public function getNext(array $oids): array
            {
                return $this->snmpClient->getNext($oids);
            }

            /** @inheritDoc */
            public function walk(string $oid, int $maxRepetitions = 20): array
            {
                return $this->snmpClient->walk($oid, $maxRepetitions);
            }
        };
    }
}
