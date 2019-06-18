<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;

final class SNMPFramework
{
    public const OID_SNMP_ENGINE_TIME = '.1.3.6.1.6.3.10.2.1.3';

    /**
     * @return iterable<string, int>
     */
    public function getEngineTime(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_SNMP_ENGINE_TIME);
    }
}
