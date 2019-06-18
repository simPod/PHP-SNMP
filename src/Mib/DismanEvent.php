<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

use SimPod\PhpSnmp\Transport\Snmp;

/**
 * See https://www.ietf.org/proceedings/43/I-D/draft-ietf-disman-event-mib-05.txt
 */
class DismanEvent
{
    public const OID_SYS_UP_TIME_INSTANCE = '.1.3.6.1.2.1.1.3.0';

    /**
     * @return iterable<string, int>
     */
    public function getSysUpTimeInstance(Snmp $snmp) : iterable
    {
        return $snmp->walk(self::OID_SYS_UP_TIME_INSTANCE);
    }
}
