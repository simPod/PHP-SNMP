<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;

final class System
{
    public const OID_DESCRIPTION = '.1.3.6.1.2.1.1.1';
    public const OID_NAME        = '.1.3.6.1.2.1.1.5.0';
    public const OID_LOCATION    = '.1.3.6.1.2.1.1.6.0';
    public const OID_UPTIME      = '.1.3.6.1.2.1.1.3.0';

    /**
     * @return iterable<string|int, string>
     */
    public function getDescription(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_DESCRIPTION);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getLocation(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_LOCATION);
    }

    /**
     * @return iterable<string|int, string>
     */
    public function getName(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_NAME);
    }

    /**
     * @return iterable<string|int, int>
     */
    public function getUptime(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_UPTIME);
    }
}
