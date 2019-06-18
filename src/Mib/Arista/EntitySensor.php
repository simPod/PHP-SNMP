<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Arista;

use SimPod\PhpSnmp\Transport\Snmp;

class EntitySensor
{
    public const OID_THRESHOLD_LOW_WARNING   = '.1.3.6.1.4.1.30065.3.12.1.1.1.1';
    public const OID_THRESHOLD_LOW_CRITICAL  = '.1.3.6.1.4.1.30065.3.12.1.1.1.2';
    public const OID_THRESHOLD_HIGH_WARNING  = '.1.3.6.1.4.1.30065.3.12.1.1.1.3';
    public const OID_THRESHOLD_HIGH_CRITICAL = '.1.3.6.1.4.1.30065.3.12.1.1.1.4';

    /**
     * @return iterable<string, int>
     */
    public function getThresholdLowWarning(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_THRESHOLD_LOW_WARNING);
    }

    /**
     * @return iterable<string, int>
     */
    public function getThresholdLowCritical(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_THRESHOLD_LOW_CRITICAL);
    }

    /**
     * @return iterable<string, int>
     */
    public function getThresholdHighWarning(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_THRESHOLD_HIGH_WARNING);
    }

    /**
     * @return iterable<string, int>
     */
    public function getThresholdHighCritical(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_THRESHOLD_HIGH_CRITICAL);
    }
}
