<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\OidWithIndex;

class Iface extends MibBase
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

    /**
     * @return int[]
     */
    public function getAdminStatuses() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_ADMIN_STATUS);
    }

    /**
     * @return string[]
     */
    public function getAliases() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_ALIAS);
    }

    /**
     * @return string[]
     */
    public function getDescriptions() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DESCRIPTION);
    }

    /**
     * @return string[]
     */
    public function getHcInOctets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_IN_OCTETS);
    }

    public function getHcInOctetsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_HC_IN_OCTETS, $index))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcOutOctets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_OUT_OCTETS);
    }

    public function getHcOutOctetsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_HC_OUT_OCTETS, $index))[$index];
    }

    public function getHcInPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_IN_UNICAST_PACKETS,
            $index
        ))[$index];
    }

    public function getHcOutPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_OUT_UNICAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcInBroadcastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_IN_BROADCAST_PACKETS);
    }

    public function getHcInBroadcastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_IN_BROADCAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcOutBroadcastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_OUT_BROADCAST_PACKETS);
    }

    public function getHcOutBroadcastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_OUT_BROADCAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcInMulticastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_IN_MULTICAST_PACKETS);
    }

    public function getHcInMulticastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_IN_MULTICAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcOutMulticastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_OUT_MULTICAST_PACKETS);
    }

    public function getHcOutMulticastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_OUT_MULTICAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcInUnicastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_IN_UNICAST_PACKETS);
    }

    public function getHcInUnicastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_IN_UNICAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return string[]
     */
    public function getHcOutUnicastPackets() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_OUT_UNICAST_PACKETS);
    }

    public function getHcOutUnicastPacketsForIndex(int $index) : string
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(
            self::OID_HC_OUT_UNICAST_PACKETS,
            $index
        ))[$index];
    }

    /**
     * @return int[]
     */
    public function getInDiscards() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_IN_DISCARDS);
    }

    public function getInDiscardsForIndex(int $index) : int
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_IN_DISCARDS, $index))[$index];
    }

    /**
     * @return int[]
     */
    public function getOutDiscards() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_OUT_DISCARDS);
    }

    public function getOutDiscardsForIndex(int $index) : int
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_OUT_DISCARDS, $index))[$index];
    }

    /**
     * @return int[]
     */
    public function getInErrors() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_IN_ERRORS);
    }

    public function getInErrorsForIndex(int $index) : int
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_IN_ERRORS, $index))[$index];
    }

    /**
     * @return int[]
     */
    public function getOutErrors() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_OUT_ERRORS);
    }

    public function getOutErrorsForIndex(int $index) : int
    {
        return $this->getSnmp()->walkFirstDegree((string) OidWithIndex::new(self::OID_OUT_ERRORS, $index))[$index];
    }

    /**
     * @return string[]
     */
    public function getNames() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_NAME);
    }

    /**
     * @return int[]
     */
    public function getOperStatuses() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_OPER_STATUS);
    }

    /**
     * @return int[]
     */
    public function getSpeeds() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_SPEED);
    }

    /**
     * @return int[]
     */
    public function getHcSpeeds() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_HC_SPEED);
    }

    /**
     * @return int[]
     */
    public function getTypes() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_TYPE);
    }

    /**
     * @return mixed[]
     */
    public function getStackTable() : iterable
    {
        return $this->getSnmp()->walk(self::OID_STACK_STATUS);
    }
}
