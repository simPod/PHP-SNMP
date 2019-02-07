<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

/**
 * See RFC 3433 https://tools.ietf.org/html/rfc3433
 */
class EntitySensor extends MibBase
{
    public const OID_PHYSICAL_SENSOR_TYPE              = '.1.3.6.1.2.1.99.1.1.1.1';
    public const OID_PHYSICAL_SENSOR_SCALE             = '.1.3.6.1.2.1.99.1.1.1.2';
    public const OID_PHYSICAL_SENSOR_PRECISION         = '.1.3.6.1.2.1.99.1.1.1.3';
    public const OID_PHYSICAL_SENSOR_VALUE             = '.1.3.6.1.2.1.99.1.1.1.4';
    public const OID_PHYSICAL_SENSOR_OPER_STATUS       = '.1.3.6.1.2.1.99.1.1.1.5';
    public const OID_PHYSICAL_SENSOR_UNITS_DISPLAY     = '.1.3.6.1.2.1.99.1.1.1.6';
    public const OID_PHYSICAL_SENSOR_VALUE_TIME_STAMP  = '.1.3.6.1.2.1.99.1.1.1.7';
    public const OID_PHYSICAL_SENSOR_VALUE_UPDATE_RATE = '.1.3.6.1.2.1.99.1.1.1.8';

    /**
     * @return int[]
     */
    public function getPhysicalSensorType() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_TYPE);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorScale() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_SCALE);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorPrecision() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_PRECISION);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorValue() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_VALUE);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorOperStatus() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_OPER_STATUS);
    }

    /**
     * @return string[]
     */
    public function getPhysicalSensorUnitsDisplay() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_UNITS_DISPLAY);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorValueTimeStamp() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_VALUE_TIME_STAMP);
    }

    /**
     * @return int[]
     */
    public function getPhysicalSensorValueUpdateRate() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_PHYSICAL_SENSOR_VALUE_UPDATE_RATE);
    }
}
