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
    public function getSystemPowerConsumptionCurrent() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_SYSTEM_POWER_CONSUMPTION_CURRENT));
    }

    /**
     * @return int[]
     */
    public function getCardAdminStatus() : array
    {
        return $this->getSnmp()->walk(self::OID_CARD_ADMIN_STATUS);
    }

    /**
     * @return int[]
     */
    public function getCardOperStatus() : array
    {
        return $this->getSnmp()->walk(self::OID_CARD_OPER_STATUS);
    }

    /**
     * @return int[]
     */
    public function getCardFanSpeedRate() : array
    {
        return $this->getSnmp()->walk(self::OID_CARD_FAN_SPEED_RATE);
    }

    /**
     * @return float[]
     */
    public function getCardTemperature() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_CARD_TEMPERATURE));
    }

    /**
     * @return string[]
     */
    public function getSubcardEquipmentNames() : array
    {
        return $this->getSnmp()->walk(self::OID_SUBCARD_EQUIPMENT_NAME);
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPower() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPower() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane1() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane2() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane3() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return float[]
     */
    public function getPortRxOpticalPowerLane4() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane1() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane2() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane3() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return float[]
     */
    public function getPortTxOpticalPowerLane4() : array
    {
        return TypeMapper::stringsToFloats($this->getSnmp()->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return string[]
     */
    public function getPortNames() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_NAME);
    }

    /**
     * @return int[]
     */
    public function getPortAdminStatuses() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_ADMIN_STATUS);
    }

    /**
     * @return int[]
     */
    public function getPortOperStatuses() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_OPER_STATUS);
    }

    /**
     * @return string[]
     */
    public function getPortAliasNames() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_ALIAS_NAME);
    }

    /**
     * @return int[]
     */
    public function getInventoryEquipmentType() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_EQUIPMENT_TYPE);
    }

    /**
     * @return string[]
     */
    public function getInventoryModuleType() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_MODULE_TYPE);
    }

    /**
     * @return string[]
     */
    public function getInventorySerialNumber() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_SERIAL_NUMBER);
    }

    /**
     * @return string[]
     */
    public function getInventoryFwVersion() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_FW_VERSION);
    }

    /**
     * @return string[]
     */
    public function getInventoryVendor() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_VENDOR);
    }

    /**
     * @return string[]
     */
    public function getInventoryPartVersion() : array
    {
        return $this->getSnmp()->walk(self::OID_INVENTORY_PART_VERSION);
    }
}
