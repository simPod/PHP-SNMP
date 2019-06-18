<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Cisco;

use SimPod\PhpSnmp\Transport\Snmp;

/**
 * See CISCO-ENVMON-MIB
 */
class EnvMon
{
    public const OID_CISCO_ENV_MON_FAN_STATUS_DESRC   = '1.3.6.1.4.1.9.9.13.1.4.1.2';
    public const OID_CISCO_ENV_MON_FAN_STATE          = '1.3.6.1.4.1.9.9.13.1.4.1.3';
    public const OID_CISCO_ENV_MON_SUPPLY_STATUS_DESC = '1.3.6.1.4.1.9.9.13.1.5.1.2';
    public const OID_CISCO_ENV_MON_SUPPLY_STATE       = '1.3.6.1.4.1.9.9.13.1.5.1.3';

    /**
     * @return iterable<string, string>
     */
    public function getCiscoEnvMonFanStatusDescr(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CISCO_ENV_MON_FAN_STATUS_DESRC);
    }

    /**
     * @return iterable<string, int>
     */
    public function getCiscoEnvMonFanState(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CISCO_ENV_MON_FAN_STATE);
    }

    /**
     * @return iterable<string, string>
     */
    public function getCiscoEnvMonSupplyStatusDescr(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CISCO_ENV_MON_SUPPLY_STATUS_DESC);
    }

    /**
     * @return iterable<string, int>
     */
    public function getCiscoEnvMonSupplyState(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_CISCO_ENV_MON_SUPPLY_STATE);
    }
}
