<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;
use function iterable_to_array;

class Iface
{
    public const OID_ADMIN_STATUS             = '.1.3.6.1.2.1.2.2.1.7';
    public const OID_ALIAS                    = '.1.3.6.1.2.1.31.1.1.1.18';
    public const OID_DESCRIPTION              = '.1.3.6.1.2.1.2.2.1.2';
    public const OID_NAME                     = '.1.3.6.1.2.1.31.1.1.1.1';
    public const OID_OPER_STATUS              = '.1.3.6.1.2.1.2.2.1.8';
    public const OID_SPEED                    = '.1.3.6.1.2.1.2.2.1.5';
    public const OID_HC_SPEED                 = '.1.3.6.1.2.1.31.1.1.1.15';
    public const OID_TYPE                     = '.1.3.6.1.2.1.2.2.1.3';
    public const OID_HC_IN_OCTETS             = '.1.3.6.1.2.1.31.1.1.1.6';
    public const OID_HC_OUT_OCTETS            = '.1.3.6.1.2.1.31.1.1.1.10';
    public const OID_HC_IN_BROADCAST_PACKETS  = '.1.3.6.1.2.1.31.1.1.1.9';
    public const OID_HC_OUT_BROADCAST_PACKETS = '.1.3.6.1.2.1.31.1.1.1.13';
    public const OID_HC_IN_MULTICAST_PACKETS  = '.1.3.6.1.2.1.31.1.1.1.8';
    public const OID_HC_OUT_MULTICAST_PACKETS = '.1.3.6.1.2.1.31.1.1.1.12';
    public const OID_HC_IN_UNICAST_PACKETS    = '.1.3.6.1.2.1.31.1.1.1.7';
    public const OID_HC_OUT_UNICAST_PACKETS   = '.1.3.6.1.2.1.31.1.1.1.11';
    public const OID_IN_ERRORS                = '.1.3.6.1.2.1.2.2.1.14';
    public const OID_OUT_ERRORS               = '.1.3.6.1.2.1.2.2.1.20';
    public const OID_IN_DISCARDS              = '.1.3.6.1.2.1.2.2.1.13';
    public const OID_OUT_DISCARDS             = '.1.3.6.1.2.1.2.2.1.19';
    public const OID_STACK_STATUS             = '.1.3.6.1.2.1.31.1.2.1.3';

    private const HC_SPEED_MULTIPLIER = 1000000;

    /**
     * @return iterable<string, int>
     */
    public function getAdminStatuses(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_ADMIN_STATUS);
    }

    /**
     * @return iterable<string, string>
     */
    public function getAliases(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_ALIAS);
    }

    /**
     * @return iterable<string, string>
     */
    public function getDescriptions(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_DESCRIPTION);
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcInOctets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_IN_OCTETS);
    }

    public function getHcInOctetsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcInOctets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcOutOctets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_OUT_OCTETS);
    }

    public function getHcOutOctetsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcOutOctets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcInPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_IN_UNICAST_PACKETS);
    }

    public function getHcInPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcInPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcOutPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_OUT_UNICAST_PACKETS);
    }

    public function getHcOutPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcOutPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcInBroadcastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_IN_BROADCAST_PACKETS);
    }

    public function getHcInBroadcastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcInBroadcastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcOutBroadcastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_OUT_BROADCAST_PACKETS);
    }

    public function getHcOutBroadcastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcOutBroadcastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcInMulticastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_IN_MULTICAST_PACKETS);
    }

    public function getHcInMulticastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcInMulticastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcOutMulticastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_OUT_MULTICAST_PACKETS);
    }

    public function getHcOutMulticastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcOutMulticastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcInUnicastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_IN_UNICAST_PACKETS);
    }

    public function getHcInUnicastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcInUnicastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getHcOutUnicastPackets(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_HC_OUT_UNICAST_PACKETS);
    }

    public function getHcOutUnicastPacketsForIndex(Snmp $snmp, int $index) : string
    {
        return iterable_to_array($this->getHcOutUnicastPackets($snmp))[$index];
    }

    /**
     * @return iterable<string, int>
     */
    public function getInDiscards(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_IN_DISCARDS);
    }

    public function getInDiscardsForIndex(Snmp $snmp, int $index) : int
    {
        return iterable_to_array($this->getInDiscards($snmp))[$index];
    }

    /**
     * @return iterable<string, int>
     */
    public function getOutDiscards(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_OUT_DISCARDS);
    }

    public function getOutDiscardsForIndex(Snmp $snmp, int $index) : int
    {
        return iterable_to_array($this->getOutDiscards($snmp))[$index];
    }

    /**
     * @return iterable<string, int>
     */
    public function getInErrors(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_IN_ERRORS);
    }

    public function getInErrorsForIndex(Snmp $snmp, int $index) : int
    {
        return iterable_to_array($this->getInErrors($snmp))[$index];
    }

    /**
     * @return iterable<string, int>
     */
    public function getOutErrors(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_OUT_ERRORS);
    }

    public function getOutErrorsForIndex(Snmp $snmp, int $index) : int
    {
        return iterable_to_array($this->getOutErrors($snmp))[$index];
    }

    /**
     * @return iterable<string, string>
     */
    public function getNames(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_NAME);
    }

    /**
     * @return iterable<string, int>
     */
    public function getOperStatuses(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_OPER_STATUS);
    }

    /**
     * @return iterable<string, int>
     */
    public function getSpeeds(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_SPEED);
    }

    /**
     * @return iterable<string, int>
     */
    public function getHcSpeeds(Snmp $snmp) : iterable
    {
        foreach ($snmp->walk(self::OID_HC_SPEED) as $key => $value) {
            yield $key => $value * self::HC_SPEED_MULTIPLIER;
        }
    }

    /**
     * @return iterable<string, int>
     */
    public function getTypes(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_TYPE);
    }

    /**
     * @return iterable<string, int>
     */
    public function getStackTable(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_STACK_STATUS);
    }
}
