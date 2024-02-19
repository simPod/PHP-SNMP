<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Helpers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Helpers\OidStripper;
use SimPod\PhpSnmp\Transport\Request;
use SimPod\PhpSnmp\Transport\SnmpClient;

#[CoversClass(OidStripper::class)]
final class OidsStripperTest extends TestCase
{
    public function testStripParent(): void
    {
        $leafOidData = [
            '.1.2.3.1' => 'a',
            '.1.2.3.2' => 'b',
            '.1.2.3.3' => 'c',
        ];

        $expected = [
            1 => 'a',
            2 => 'b',
            3 => 'c',
        ];

        self::assertSame($expected, OidStripper::stripParent($leafOidData));
    }

    public function testStripParentEmptyData(): void
    {
        $this->expectExceptionObject(GeneralException::new('Expected non-empty array'));

        OidStripper::stripParent([]);
    }

    public function testStripParentInvalidKeys(): void
    {
        $this->expectExceptionObject(GeneralException::new('Expected keys to be full OIDs'));

        OidStripper::stripParent(['something strange' => 123]);
    }

    public function testBatchStripParent(): void
    {
        $leafOidDataResponses = [
            [
                '.1.2.3.1' => 'a',
                '.1.2.3.2' => 'b',
                '.1.2.3.3' => 'c',
            ],
            ['.4.5.6.8' => 'd'],
        ];

        $expected = [
            [
                1 => 'a',
                2 => 'b',
                3 => 'c',
            ],
            [8 => 'd'],
        ];

        self::assertSame($expected, OidStripper::batchStripParent($leafOidDataResponses));
    }

    public function testBatchStripParentEmptyData(): void
    {
        $this->expectExceptionObject(GeneralException::new('Expected non-empty array'));

        OidStripper::batchStripParent([[]]);
    }

    public function testBatchStripParentInvalidKeys(): void
    {
        $this->expectExceptionObject(GeneralException::new('Expected keys to be full OIDs'));

        OidStripper::batchStripParent([['something strange' => 123]]);
    }

    public function testWalk(): void
    {
        $response = [
            [
                '.1.2.3.1' => 'a',
                '.1.2.3.2' => 'b',
                '.1.2.3.3' => 'c',
            ],
        ];

        $expected = [
            '3.1' => 'a',
            '3.2' => 'b',
            '3.3' => 'c',
        ];

        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient
            ->expects(self::once())
            ->method('batch')
            ->with([Request::walk($oid = '.1.2')])
            ->willReturn($response);

        self::assertSame($expected, OidStripper::walk($snmpClient, $oid));
    }

    public function testBatchStripParentPrefix(): void
    {
        $requests = [
            'walk' => Request::walk('.1.2'),
            'get' => Request::get(['.1.3.6', '.1.3.4']),
            'getNext' => Request::getNext(['.1.3.6', '.1.3.3.666']),
        ];

        $responses = [
            'walk' => [
                '.1.2.3.1' => 'a',
                '.1.2.3.2' => 'b',
                '.1.2.3.3' => 'c',
            ],
            'get' => [
                '.1.3.6.1.2' => 'd',
                '.1.3.4.1' => 'e',
            ],
            'getNext' => [
                '.1.3.6.1.1' => 'f',
                '.1.3.4.1' => 'g',
            ],
        ];

        $expected = [
            'walk' => [
                '3.1' => 'a',
                '3.2' => 'b',
                '3.3' => 'c',
            ],
            'get' => [
                '1.2' => 'd',
                '1' => 'e',
            ],
            'getNext' => [
                '1.1' => 'f',
                '.1.3.4.1' => 'g',
            ],
        ];

        $snmpClient = $this->createMock(SnmpClient::class);
        $snmpClient->expects(self::once())->method('batch')->with($requests)->willReturn($responses);

        self::assertSame($expected, OidStripper::batchStripParentPrefix($snmpClient, $requests));
    }
}
