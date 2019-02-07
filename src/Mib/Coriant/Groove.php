<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Coriant;

use SimPod\PhpSnmp\Helper\TypeMapper;
use SimPod\PhpSnmp\Mib\MibBase;

/**
 * See Groove G30 release 2.1.0 https://mibs.observium.org/mib/CORIANT-GROOVE-MIB/
 */
class Groove extends MibBase
{
    public const OID_SYSTEM_POWER_CONSUMPTION_CURRENT = '.1.3.6.1.4.1.42229.1.2.2.2.2';
    public const OID_CARD_ADMIN_STATUS                = '.1.3.6.1.4.1.42229.1.2.3.3.1.1.3';
    public const OID_CARD_OPER_STATUS                 = '.1.3.6.1.4.1.42229.1.2.3.3.1.1.4';
    public const OID_CARD_FAN_SPEED_RATE              = '.1.3.6.1.4.1.42229.1.2.3.3.1.1.7';
    public const OID_CARD_TEMPERATURE                 = '.1.3.6.1.4.1.42229.1.2.3.3.1.1.9';
    public const OID_SUBCARD_EQUIPMENT_NAME           = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.2';
    public const OID_PORT_RX_OPTICAL_POWER            = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.4';
    public const OID_PORT_TX_OPTICAL_POWER            = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.5';
    public const OID_PORT_RX_OPTICAL_POWER_LANE_1     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.7';
    public const OID_PORT_RX_OPTICAL_POWER_LANE_2     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.8';
    public const OID_PORT_RX_OPTICAL_POWER_LANE_3     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.9';
    public const OID_PORT_RX_OPTICAL_POWER_LANE_4     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.10';
    public const OID_PORT_TX_OPTICAL_POWER_LANE_1     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.11';
    public const OID_PORT_TX_OPTICAL_POWER_LANE_2     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.12';
    public const OID_PORT_TX_OPTICAL_POWER_LANE_3     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.13';
    public const OID_PORT_TX_OPTICAL_POWER_LANE_4     = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.14';
    public const OID_PORT_NAME                        = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.16';
    public const OID_PORT_ADMIN_STATUS                = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.19';
    public const OID_PORT_OPER_STATUS                 = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.20';
    public const OID_PORT_ALIAS_NAME                  = '.1.3.6.1.4.1.42229.1.2.3.6.1.1.22';
    public const OID_INVENTORY_EQUIPMENT_TYPE         = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.1';
    public const OID_INVENTORY_MODULE_TYPE            = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.7';
    public const OID_INVENTORY_VENDOR                 = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.8';
    public const OID_INVENTORY_SERIAL_NUMBER          = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.9';
    public const OID_INVENTORY_FW_VERSION             = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.10';
    public const OID_INVENTORY_PART_VERSION           = '.1.3.6.1.4.1.42229.1.2.3.12.1.1.11';

    /**
     * @return float[]
     */
    public function getSystemPowerConsumptionCurrent() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_SYSTEM_POWER_CONSUMPTION_CURRENT));
    }

    /**
     * @return int[]
     */
    public function getCardAdminStatus() : iterable
    {
        return $this->getSnmp()->walk(self::OID_CARD_ADMIN_STATUS);
    }

    /**
     * @return int[]
     */
    public function getCardOperStatus() : iterable
    {
        return $this->getSnmp()->walk(self::OID_CARD_OPER_STATUS);
    }

    /**
     * @return int[]
     */
    public function getCardFanSpeedRate() : iterable
    {
        return $this->getSnmp()->walk(self::OID_CARD_FAN_SPEED_RATE);
    }

    /**
     * @return float[]
     */
    public function getCardTemperature() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_CARD_TEMPERATURE));
    }

    /**
     * @return string[]
     */
    public function getSubcardEquipmentNames() : iterable
    {
        return $this->getSnmp()->walk(self::OID_SUBCARD_EQUIPMENT_NAME);
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPower() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPower() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane1() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane2() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane3() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane4() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane1() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane2() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane3() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane4() : iterable
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return string[]
     */
    public function getPortNames() : iterable
    {
        return $this->getSnmp()->walk(self::OID_PORT_NAME);
    }

    /**
     * @return int[]
     */
    public function getPortAdminStatuses() : iterable
    {
        return $this->getSnmp()->walk(self::OID_PORT_ADMIN_STATUS);
    }

    /**
     * @return int[]
     */
    public function getPortOperStatuses() : iterable
    {
        return $this->getSnmp()->walk(self::OID_PORT_OPER_STATUS);
    }

    /**
     * @return string[]
     */
    public function getPortAliasNames() : iterable
    {
        return $this->getSnmp()->walk(self::OID_PORT_ALIAS_NAME);
    }

    /**
     * @return int[]
     */
    public function getInventoryEquipmentType() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_EQUIPMENT_TYPE);
    }

    /**
     * @return string[]
     */
    public function getInventoryModuleType() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_MODULE_TYPE);
    }

    /**
     * @return string[]
     */
    public function getInventorySerialNumber() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_SERIAL_NUMBER);
    }

    /**
     * @return string[]
     */
    public function getInventoryFwVersion() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_FW_VERSION);
    }

    /**
     * @return string[]
     */
    public function getInventoryVendor() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_VENDOR);
    }

    /**
     * @return string[]
     */
    public function getInventoryPartVersion() : iterable
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_PART_VERSION);
    }
}
