<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Cisco;

use SimPod\PhpSnmp\Mib\MibBase;

/**
 * See CISCO-ENVMON-MIB
 */
class EnvMon extends MibBase
{
    public const OID_CISCO_ENV_MON_FAN_STATUS_DESRC   = '1.3.6.1.4.1.9.9.13.1.4.1.2';
    public const OID_CISCO_ENV_MON_FAN_STATE          = '1.3.6.1.4.1.9.9.13.1.4.1.3';
    public const OID_CISCO_ENV_MON_SUPPLY_STATUS_DESC = '1.3.6.1.4.1.9.9.13.1.5.1.2';
    public const OID_CISCO_ENV_MON_SUPPLY_STATE       = '1.3.6.1.4.1.9.9.13.1.5.1.3';

    /** @return string[] */
    public function getCiscoEnvMonFanStatusDescr() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_CISCO_ENV_MON_FAN_STATUS_DESRC);
    }

    /** @return int[] */
    public function getCiscoEnvMonFanState() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_CISCO_ENV_MON_FAN_STATE);
    }

    /** @return string[] */
    public function getCiscoEnvMonSupplyStatusDescr() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_CISCO_ENV_MON_SUPPLY_STATUS_DESC);
    }

    /** @return int[] */
    public function getCiscoEnvMonSupplyState() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_CISCO_ENV_MON_SUPPLY_STATE);
    }
}
