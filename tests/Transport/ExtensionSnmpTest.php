<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Transport;

use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\SnmpExtensionError;
use SimPod\PhpSnmp\Transport\ExtensionSnmp;
use function iterable_to_array;
use function proc_get_status;
use function proc_open;
use function shell_exec;
use function sprintf;

/**
 * @group snmpExtension
 */
final class ExtensionSnmpTest extends TestCase
{
    private const SNMP_HOST = '127.0.0.1:15000';

    /** @var resource|null */
    private static $process;

    public static function setUpBeforeClass() : void
    {
        $command = sprintf(
            'snmpsimd.py --v2c-arch --data-dir %s --agent-udpv4-endpoint %s',
            __DIR__ . '/data',
            self::SNMP_HOST
        );

        $process = proc_open(
            $command,
            [
                0 => ['file', '/dev/null', 'w'],
                2 => ['file', '/dev/null', 'w'],
            ],
            $pipes
        );

        if ($process === false) {
            self::fail('failed to initiate SNMP agent');

            return;
        }

        self::$process = $process;
    }

    public static function tearDownAfterClass() : void
    {
        if (self::$process !== null) {
            shell_exec(sprintf('pkill -2 -P %d', proc_get_status(self::$process)['pid']));
            self::$process = null;
        }
    }

    public function __destruct()
    {
        self::tearDownAfterClass();
    }

    public function testWalk() : void
    {
        $result = $this->getSnmp()->walk('.1.3.6.1.2.1.2.2.1.2');

        self::assertSame(
            [
                '47' => 'Ethernet47',
                '48' => 'Ethernet48',
                '49001' => 'Ethernet49/1',
                '50001' => 'Ethernet50/1',
                '1000008' => 'Port-Channel8',
                '1000009' => 'Port-Channel9',
                '2002002' => 'Vlan2002',
                '2002019' => 'Vlan2019',
                '2002020' => 'Vlan2020',
                '5000000' => 'Loopback0',
            ],
            iterable_to_array($result)
        );
    }

    public function testWalkWithInvalidVersion() : void
    {
        $this->expectException(SnmpExtensionError::class);
        $this->expectExceptionMessage('Invalid SNMP version');

        iterable_to_array((new ExtensionSnmp('', '', 0, 0, 'invalid'))->walk('.1.15'));
    }

    public function testWalkWithOutOfRangeError() : void
    {
        $this->expectException(SnmpExtensionError::class);
        $this->expectExceptionMessage('is out of the MIB tree range');

        iterable_to_array($this->getSnmp()->walk('.1.15'));
    }

    public function testWalkWithGenericError() : void
    {
        $this->expectException(SnmpExtensionError::class);
        $this->expectExceptionMessage('Could not perform walk');

        iterable_to_array((new ExtensionSnmp('some invalid host'))->walk('.1.15'));
    }

    public function testWalkWithUnknownType() : void
    {
        $this->expectException(SnmpExtensionError::class);
        $this->expectExceptionMessage('Encountered unknown type "OPAQUE"');

        iterable_to_array($this->getSnmp()->walk('.1.6.6.6.666'));
    }

    public function testWalkWithCompleteOids() : void
    {
        $result = $this->getSnmp()->walkWithCompleteOids('.1.3.6.1');

        self::assertSame(
            [
                '.1.3.6.1.2.1.1.3.0' => 293718542,
                '.1.3.6.1.2.1.2.2.1.2.47' => 'Ethernet47',
                '.1.3.6.1.2.1.2.2.1.2.48' => 'Ethernet48',
                '.1.3.6.1.2.1.2.2.1.2.49001' => 'Ethernet49/1',
                '.1.3.6.1.2.1.2.2.1.2.50001' => 'Ethernet50/1',
                '.1.3.6.1.2.1.2.2.1.2.1000008' => 'Port-Channel8',
                '.1.3.6.1.2.1.2.2.1.2.1000009' => 'Port-Channel9',
                '.1.3.6.1.2.1.2.2.1.2.2002002' => 'Vlan2002',
                '.1.3.6.1.2.1.2.2.1.2.2002019' => 'Vlan2019',
                '.1.3.6.1.2.1.2.2.1.2.2002020' => 'Vlan2020',
                '.1.3.6.1.2.1.2.2.1.2.5000000' => 'Loopback0',
                '.1.3.6.1.2.1.2.2.1.14.8' => 0,
                '.1.3.6.1.2.1.2.2.1.14.9' => 226,
                '.1.3.6.1.2.1.2.2.1.14.10' => 256,
                '.1.3.6.1.2.1.2.2.1.14.11' => 296,
                '.1.3.6.1.2.1.4.20.1.1.10.100.192.2' => '10.100.192.2',
                '.1.3.6.1.2.1.4.20.1.1.10.110.27.254' => '10.110.27.254',
                '.1.3.6.1.2.1.4.20.1.1.66.208.216.74' => '66.208.216.74',
                '.1.3.6.1.2.1.4.22.1.2.2000955.185.152.67.97' => '91 E2 BA E3 5A 61',
                '.1.3.6.1.2.1.4.22.1.2.2000955.185.152.67.99' => '53 54 00 5F 41 D0',
                '.1.3.6.1.2.1.4.22.1.2.2000955.185.152.67.100' => '53 54 00 4C 5A 5D',
                '.1.3.6.1.2.1.4.22.1.2.2000955.185.152.67.102' => '53 54 00 A9 A8 3B',
                '.1.3.6.1.2.1.4.22.1.2.2000955.185.152.67.104' => '53 54 00 5A A0 CA',
                '.1.3.6.1.2.1.25.2.3.1.2.1' => '.1.3.6.1.2.1.25.2.1.2',
                '.1.3.6.1.2.1.25.2.3.1.2.2' => '.1.3.6.1.2.1.25.2.1.2',
                '.1.3.6.1.2.1.25.2.3.1.2.3' => '.1.3.6.1.2.1.25.2.1.2',
                '.1.3.6.1.2.1.25.2.3.1.2.4' => '.1.3.6.1.2.1.25.2.1.9',
                '.1.3.6.1.2.1.31.1.1.1.6.46' => '1884401752869190',
                '.1.3.6.1.2.1.31.1.1.1.6.47' => '1883620653799494',
                '.1.3.6.1.2.1.31.1.1.1.6.48' => '1884283891426650',
                '.1.3.6.1.2.1.31.1.1.1.6.49001' => '2494191363092125',
                '.1.3.6.1.2.1.31.1.1.1.6.50001' => '17658827020872235',
                '.1.3.6.1.2.1.31.1.1.1.15.1000001' => 100000,
                '.1.3.6.1.2.1.31.1.1.1.15.1000003' => 60000,
                '.1.3.6.1.2.1.31.1.1.1.15.1000005' => 80000,
                '.1.3.6.1.6.3.10.2.1.3.0' => 2937024,
            ],
            iterable_to_array($result)
        );
    }

    private function getSnmp() : ExtensionSnmp
    {
        return new ExtensionSnmp(self::SNMP_HOST);
    }
}
