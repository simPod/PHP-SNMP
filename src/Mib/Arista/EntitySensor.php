<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Arista;

use SimPod\PhpSnmp\Mib\MibBase;

class EntitySensor extends MibBase
{
    public const OID_THRESHOLD_LOW_WARNING   = '.1.3.6.1.4.1.30065.3.12.1.1.1.1';
    public const OID_THRESHOLD_LOW_CRITICAL  = '.1.3.6.1.4.1.30065.3.12.1.1.1.2';
    public const OID_THRESHOLD_HIGH_WARNING  = '.1.3.6.1.4.1.30065.3.12.1.1.1.3';
    public const OID_THRESHOLD_HIGH_CRITICAL = '.1.3.6.1.4.1.30065.3.12.1.1.1.4';

    /** @return int[] */
    public function getThresholdLowWarning() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_THRESHOLD_LOW_WARNING);
    }

    /** @return int[] */
    public function getThresholdLowCritical() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_THRESHOLD_LOW_CRITICAL);
    }

    /** @return int[] */
    public function getThresholdHighWarning() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_THRESHOLD_HIGH_WARNING);
    }

    /** @return int[] */
    public function getThresholdHighCritical() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_THRESHOLD_HIGH_CRITICAL);
    }
}
