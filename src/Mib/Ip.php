<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Helper\MacAddress;
use SimPod\PhpSnmp\Transport\Snmp;

class Ip
{
    public const OID_IP_ADDRESS                   = '.1.3.6.1.2.1.4.20.1.1';
    public const OID_IP_NET_TO_MEDIA_PHYS_ADDRESS = '.1.3.6.1.2.1.4.22.1.2';

    /**
     * @return iterable<string, string>
     */
    public function getIpAddress(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_IP_ADDRESS);
    }

    /**
     * @return iterable<string, string>
     */
    public function getIpNetToMediaPhysAddress(Snmp $snmp) : iterable
    {
        return MacAddress::normalizeBulk($snmp->walk(self::OID_IP_NET_TO_MEDIA_PHYS_ADDRESS));
    }
}
