<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

final class Lldp extends MibBase
{
    public const OID_LOCAL_PORT_ID            = '.1.0.8802.1.1.2.1.3.7.1.3';
    public const OID_REMOTE_PORT_ID           = '.1.0.8802.1.1.2.1.4.1.1.7';
    public const OID_REMOTE_SYS_NAME          = '.1.0.8802.1.1.2.1.4.1.1.9';
    public const OID_PORT_CONFIG_ADMIN_STATUS = '.1.0.8802.1.1.2.1.1.6.1.2';

    /**
     * @return string[]
     */
    public function getLocalPortIds() : iterable
    {
        return $this->getSnmp()->walk(self::OID_LOCAL_PORT_ID);
    }

    /**
     * @return string[]
     */
    public function getRemotePortIds() : iterable
    {
        return $this->getSnmp()->walk(self::OID_REMOTE_PORT_ID);
    }

    /**
     * @return string[]
     */
    public function getRemoteSysNames() : iterable
    {
        return $this->getSnmp()->walk(self::OID_REMOTE_SYS_NAME);
    }

    /**
     * @return int[]
     */
    public function getPortConfigAdminStatus() : iterable
    {
        return $this->getSnmp()->walk(self::OID_PORT_CONFIG_ADMIN_STATUS);
    }
}
