<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib\Coriant;

use SimPod\PhpSnmp\Mib\MibBase;

/**
 * See Groove G30 release 2.1.0 https://mibs.observium.org/mib/CORIANT-GROOVE-MIB/
 */
class Groove extends MibBase
{
    public const OID_PORT_NAME         = '1.3.6.1.4.1.42229.1.2.3.6.1.1.16';
    public const OID_PORT_ADMIN_STATUS = '1.3.6.1.4.1.42229.1.2.3.6.1.1.19';
    public const OID_PORT_OPER_STATUS  = '1.3.6.1.4.1.42229.1.2.3.6.1.1.20';
    public const OID_PORT_ALIAS_NAME   = '1.3.6.1.4.1.42229.1.2.3.6.1.1.22';

    /**
     * @return string[]
     */
    public function getPortNames() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_NAME);
    }

    /**
     * @return int[]
     */
    public function getPortAdminStatuses() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_ADMIN_STATUS);
    }

    /**
     * @return int[]
     */
    public function getPortOperStatuses() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_OPER_STATUS);
    }

    /**
     * @return string[]
     */
    public function getPortAliasNames() : array
    {
        return $this->getSnmp()->walk(self::OID_PORT_ALIAS_NAME);
    }
}
