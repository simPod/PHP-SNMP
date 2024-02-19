<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Transport;

use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\NoRequestsProvided;
use SimPod\PhpSnmp\Transport\Request;
use SimPod\PhpSnmp\Transport\SimpleBatch;
use SimPod\PhpSnmp\Transport\SnmpClient;

final class SimpleBatchTest extends TestCase
{
    /**
     * @param list<Request> $requests
     * @param list<array<string, mixed>> $result
     *
     * @dataProvider providerBatch
     */
    public function testBatch(SnmpClient $snmpClient, array $requests, array $result): void
    {
        $batchSnmpClient = $this->createBatchSnmpClient($snmpClient);

        self::assertSame($result, $batchSnmpClient->batch($requests));
    }

    /** @return iterable<mixed> */
    public function providerBatch(): iterable
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())->method('get')->with(['.1.2.3'])->willReturn(['.1.2.3' => 123]);

        yield 'single get' => [$snmpClient, [Request::get(['.1.2.3'])], [['.1.2.3' => 123]]];

        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())->method('getNext')->with(['.1.2.3'])->willReturn(['.1.2.3.1' => 1231]);

        yield 'single getNext' => [$snmpClient, [Request::getNext(['.1.2.3'])], [['.1.2.3.1' => 1231]]];

        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())->method('walk')->with('.1.2.3', 10)->willReturn(['.1.2.3.4.5' => 12345]);

        yield 'single walk' => [$snmpClient, [Request::walk('.1.2.3', 10)], [['.1.2.3.4.5' => 12345]]];

        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient
            ->expects(self::once())
            ->id('get')
            ->method('get')
            ->with(['.1.2.3', '.4.5.6'])
            ->willReturn(['.1.2.3' => 123, '.4.5.6' => 456]);

        $snmpClient
            ->expects(self::exactly(2))
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
            ->expects(self::once())
            ->after('walk')
            ->method('getNext')
            ->with(['.7.8.9'])
            ->willReturn(['.7.8.9.1' => 7891]);

        yield 'multiple requests' => [
            $snmpClient,
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

            public function __construct(private SnmpClient $snmpClient)
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
