<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Helpers;

use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Helpers\ValueGetter;
use SimPod\PhpSnmp\Tests\BaseTestCase;
use SimPod\PhpSnmp\Transport\SnmpClient;

final class ValueGetterTest extends BaseTestCase
{
    public function testFirst(): void
    {
        $response = ['.1.2.3.1' => $expected = 'a'];

        self::assertSame($expected, ValueGetter::first($response));
    }

    public function testFirstWithUnexpectedData(): void
    {
        $this->expectExceptionObject(GeneralException::new('Expected non-empty array'));

        ValueGetter::first([]);
    }

    public function testFirstFromSameTree(): void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(['.1.2.3.1' => $expected = 'a']);

        self::assertSame($expected, ValueGetter::firstFromSameTree($snmpClient, '.1.2.3'));
    }

    public function testFirstFromSameTreeDoesntExist(): void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(['.1.2.4.1' => 'a']);

        self::assertSnmpException(
            NoSuchInstanceExists::fromOid('', '.1.2.3'),
            static function () use ($snmpClient): void {
                ValueGetter::firstFromSameTree($snmpClient, '.1.2.3');
            },
        );
    }

    public function testFirstFromSameTrees(): void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(
                [
                    '.1.2.3.1' => 'a',
                    '.1.2.6.1' => 'b',
                ],
            );

        $expected = ['a', 'b'];

        self::assertSame($expected, ValueGetter::firstFromSameTrees($snmpClient, ['.1.2.3', '.1.2.6']));
    }

    public function testFirstFromSameTreesDoesntExist(): void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(
                [
                    '.1.2.3.1' => 'a',
                    '.1.2.7.1' => 'b',
                ],
            );

        self::assertSnmpException(
            NoSuchInstanceExists::fromOid('', '.1.2.6'),
            static function () use ($snmpClient): void {
                ValueGetter::firstFromSameTrees($snmpClient, ['.1.2.3', '.1.2.6']);
            },
        );
    }
}
