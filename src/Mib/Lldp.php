<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;

final class Lldp
{
    public const OID_LOCAL_PORT_ID            = '.1.0.8802.1.1.2.1.3.7.1.3';
    public const OID_REMOTE_PORT_ID           = '.1.0.8802.1.1.2.1.4.1.1.7';
    public const OID_REMOTE_SYS_NAME          = '.1.0.8802.1.1.2.1.4.1.1.9';
    public const OID_PORT_CONFIG_ADMIN_STATUS = '.1.0.8802.1.1.2.1.1.6.1.2';

    /**
     * @return iterable<string, string>
     */
    public function getLocalPortIds(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_LOCAL_PORT_ID);
    }

    /**
     * @return iterable<string, string>
     */
    public function getRemotePortIds(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_REMOTE_PORT_ID);
    }

    /**
     * @return iterable<string, string>
     */
    public function getRemoteSysNames(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_REMOTE_SYS_NAME);
    }

    /**
     * @return iterable<string, int>
     */
    public function getPortConfigAdminStatus(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_PORT_CONFIG_ADMIN_STATUS);
    }
}
