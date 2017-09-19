<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Dcp;

use SimPod\PhpSnmp\Mib\MibBase;

/**
 * See iso(1).org(3).dod(6).internet(1).private(4).enterprise(1).smartoptics(30826).dcp(2).dcpGeneric(2).dcpInterface(1)
 */
class DcpInterface extends MibBase
{
    public const OID_DCP_INTERFACE_INDEX      = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.1';
    public const OID_DCP_INTERFACE_NAME       = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.2';
    public const OID_DCP_INTERFACE_RX_POWER   = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.3';
    public const OID_DCP_INTERFACE_TX_POWER   = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.4';
    public const OID_DCP_INTERFACE_STATUS     = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.5';
    public const OID_DCP_INTERFACE_ALARM      = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.6';
    public const OID_DCP_INTERFACE_FORMAT     = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.7';
    public const OID_DCP_INTERFACE_WAVELENGTH = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.8';
    public const OID_DCP_INTERFACE_CHANNEL_ID = '.1.3.6.1.4.1.30826.2.2.1.1.1.1.9';

    /** @return array<int, int> */
    public function getDcpInterfaceIndex() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_INDEX);
    }

    /** @return array<int, string> */
    public function getDcpInterfaceName() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_NAME);
    }

    /** @return array<int, int> */
    public function getDcpInterfaceRxPower() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_RX_POWER);
    }

    /** @return array<int, int> */
    public function getDcpInterfaceTxPower() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_TX_POWER);
    }

    /**
     * The operational state for the interface. idle(1), down(2), up(3)
     *
     * @return array<int, int>
     */
    public function getDcpInterfaceStatus() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_STATUS);
    }

    /** @return array<int, int> */
    public function getDcpInterfaceAlarm() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_ALARM);
    }

    /** @return array<int, string> */
    public function getDcpInterfaceFormat() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_FORMAT);
    }

    /** @return array<int, float> */
    public function getDcpInterfaceWavelength() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_WAVELENGTH);
    }

    /** @return array<int, string> */
    public function getDcpInterfaceChannelId() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_DCP_INTERFACE_CHANNEL_ID);
    }
}
