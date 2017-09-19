<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

/**
 * See https://www.ietf.org/proceedings/43/I-D/draft-ietf-disman-event-mib-05.txt
 */
class DismanEvent extends MibBase
{
    public const OID_SYS_UP_TIME_INSTANCE = '.1.3.6.1.2.1.1.3.0';

    /** @return int[] */
    public function getSysUpTimeInstance() : array
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_SYS_UP_TIME_INSTANCE);
    }
}
