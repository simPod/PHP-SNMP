<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Coriant;

use SimPod\PhpSnmp\Helper\TypeMapper;
use SimPod\PhpSnmp\Transport\Snmp;

/**
 * See Groove G30 release 2.1.0 https://mibs.observium.org/mib/CORIANT-GROOVE-MIB/
 */
class Groove
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
     * @return iterable<string, float>
     */
    public function getSystemPowerConsumptionCurrent(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_SYSTEM_POWER_CONSUMPTION_CURRENT));
    }

    /**
     * @return iterable<string, int>
     */
    public function getCardAdminStatus(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CARD_ADMIN_STATUS);
    }

    /**
     * @return iterable<string, int>
     */
    public function getCardOperStatus(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CARD_OPER_STATUS);
    }

    /**
     * @return iterable<string, int>
     */
    public function getCardFanSpeedRate(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CARD_FAN_SPEED_RATE);
    }

    /**
     * @return iterable<string, float>
     */
    public function getCardTemperature(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_CARD_TEMPERATURE));
    }

    /**
     * @return iterable<string, string>
     */
    public function getSubcardEquipmentNames(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_SUBCARD_EQUIPMENT_NAME);
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortRxOpticalPower(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_RX_OPTICAL_POWER));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortTxOpticalPower(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_TX_OPTICAL_POWER));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortRxOpticalPowerLane1(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortRxOpticalPowerLane2(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortRxOpticalPowerLane3(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortRxOpticalPowerLane4(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_RX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortTxOpticalPowerLane1(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_1));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortTxOpticalPowerLane2(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_2));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortTxOpticalPowerLane3(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_3));
    }

    /**
     * @return iterable<string, float>
     */
    public function getPortTxOpticalPowerLane4(Snmp $snmp) : iterable
    {
        return TypeMapper::stringsToFloats($snmp->walk(self::OID_PORT_TX_OPTICAL_POWER_LANE_4));
    }

    /**
     * @return iterable<string, string>
     */
    public function getPortNames(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PORT_NAME);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPortAdminStatuses(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PORT_ADMIN_STATUS);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPortOperStatuses(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PORT_OPER_STATUS);
    }

    /**
     * @return iterable<string, string>
     */
    public function getPortAliasNames(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PORT_ALIAS_NAME);
    }

    /**
     * @return iterable<string, int>
     */
    public function getInventoryEquipmentType(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_EQUIPMENT_TYPE);
    }

    /**
     * @return iterable<string, string>
     */
    public function getInventoryModuleType(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_MODULE_TYPE);
    }

    /**
     * @return iterable<string, string>
     */
    public function getInventorySerialNumber(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_SERIAL_NUMBER);
    }

    /**
     * @return iterable<string, string>
     */
    public function getInventoryFwVersion(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_FW_VERSION);
    }

    /**
     * @return iterable<string, string>
     */
    public function getInventoryVendor(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_VENDOR);
    }

    /**
     * @return iterable<string, string>
     */
    public function getInventoryPartVersion(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_INVENTORY_PART_VERSION);
    }
}
