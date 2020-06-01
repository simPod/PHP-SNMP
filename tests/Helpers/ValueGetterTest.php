<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Helpers\ValueGetter;
use SimPod\PhpSnmp\Transport\SnmpClient;

final class ValueGetterTest extends TestCase
{
    public function testFirst() : void
    {
        $response = ['.1.2.3.1' => $expected = 'a'];

        self::assertSame($expected, ValueGetter::first($response));
    }

    public function testFirstWithUnexpectedData() : void
    {
        $this->expectExceptionObject(GeneralException::new('Expected non-empty array'));

        ValueGetter::first([]);
    }

    public function testFirstFromSameTree() : void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(['.1.2.3.1' => $expected = 'a']);

        self::assertSame($expected, ValueGetter::firstFromSameTree($snmpClient, '.1.2.3'));
    }

    public function testFirstFromSameTreeDoesntExist() : void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(['.1.2.4.1' => 'a']);

        $this->expectExceptionObject(NoSuchInstanceExists::fromOid('.1.2.3'));
        ValueGetter::firstFromSameTree($snmpClient, '.1.2.3');
    }

    public function testFirstFromSameTrees() : void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(
                [
                    '.1.2.3.1' => 'a',
                    '.1.2.6.1' => 'b',
                ]
            );

        $expected = ['a', 'b'];

        self::assertSame($expected, ValueGetter::firstFromSameTrees($snmpClient, ['.1.2.3', '.1.2.6']));
    }

    public function testFirstFromSameTreesDoesntExist() : void
    {
        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())
            ->method('getNext')
            ->willReturn(
                [
                    '.1.2.3.1' => 'a',
                    '.1.2.7.1' => 'b',
                ]
            );

        $this->expectExceptionObject(NoSuchInstanceExists::fromOid('.1.2.6'));
        ValueGetter::firstFromSameTrees($snmpClient, ['.1.2.3', '.1.2.6']);
    }
}
