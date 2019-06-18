<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Cisco;

use SimPod\PhpSnmp\Transport\Snmp;

/**
 * See CISCO-ENTITY-SENSOR-MIB
 */
class EntitySensor
{
    public const OID_PHYSICAL_SENSOR_TYPE              = '.1.3.6.1.4.1.9.9.91.1.1.1.1.1';
    public const OID_PHYSICAL_SENSOR_SCALE             = '.1.3.6.1.4.1.9.9.91.1.1.1.1.2';
    public const OID_PHYSICAL_SENSOR_PRECISION         = '.1.3.6.1.4.1.9.9.91.1.1.1.1.3';
    public const OID_PHYSICAL_SENSOR_VALUE             = '.1.3.6.1.4.1.9.9.91.1.1.1.1.4';
    public const OID_PHYSICAL_SENSOR_OPER_STATUS       = '.1.3.6.1.4.1.9.9.91.1.1.1.1.5';
    public const OID_PHYSICAL_SENSOR_UNITS_DISPLAY     = '.1.3.6.1.4.1.9.9.91.1.1.1.1.6';
    public const OID_PHYSICAL_SENSOR_VALUE_TIME_STAMP  = '.1.3.6.1.4.1.9.9.91.1.1.1.1.7';
    public const OID_PHYSICAL_SENSOR_VALUE_UPDATE_RATE = '.1.3.6.1.4.1.9.9.91.1.1.1.1.8';

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorType(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_TYPE);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorScale(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_SCALE);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorPrecision(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_PRECISION);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorValue(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_VALUE);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorOperStatus(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_OPER_STATUS);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorUnitsDisplay(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_UNITS_DISPLAY);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorValueTimeStamp(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_VALUE_TIME_STAMP);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPhysicalSensorValueUpdateRate(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PHYSICAL_SENSOR_VALUE_UPDATE_RATE);
    }
}
