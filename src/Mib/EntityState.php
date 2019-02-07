<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Mib;

/**
 * See RFC 4268 https://tools.ietf.org/html/rfc4268
 */
class EntityState extends MibBase
{
    public const OID_ENT_STATE_ADMIN = '.1.3.6.1.2.1.131.1.1.1.2';
    public const OID_ENT_STATE_OPER  = '.1.3.6.1.2.1.131.1.1.1.3';

    /**
     * @return int[]
     */
    public function getEntStateAdmin() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_ENT_STATE_ADMIN);
    }

    /**
     * @return int[]
     */
    public function getEntStateOper() : iterable
    {
        return $this->getSnmp()->walkFirstDegree(self::OID_ENT_STATE_OPER);
    }
}
