<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use FreeDSx\Snmp\SnmpClient;
use SimPod\PhpSnmp\Exception\SnmpFailed;
use function assert;
use function is_int;
use function strrpos;
use function substr;

final class FreedsxSnmp implements Snmp
{
    /** @var SnmpClient */
    private $client;

    public function __construct(

        string $host = '127.0.0.1',
        string $community = 'public',
//        int $timeout = 1000000,
//        int $retry = 5,
        int $version = 2
//        string $secLevel = 'noAuthNoPriv',
//        string $authProtocol = 'MD5',
//        string $authPassphrase = 'None',
//        string $privProtocol = 'DES',
//        string $privPassphrase = 'None'
    )
    {
        $this->client = new SnmpClient([
            'host'      => $host,
            'version'   => $version,
            'community' => $community,
        ]);

//        $this->retry     = $retry;
//        $this->timeout   = $timeout;
//        $this->version   = $version;
//
//        $this->secName        = $community;
//        $this->secLevel       = $secLevel;
//        $this->authProtocol   = $authProtocol;
//        $this->authPassphrase = $authPassphrase;
//        $this->privProtocol   = $privProtocol;
//        $this->privPassphrase = $privPassphrase;
//        snmp_set_oid_output_format(SNMP_OID_OUTPUT_NUMERIC);
    }

    /**
     * @return mixed[]
     */
    public function walkFirstDegree(string $oid) : iterable
    {
        $result = $this->walk($oid);

        $oidPrefix = null;
        foreach ($result as $oid => $value) {
            $length = strrpos($oid, '.');
            assert(is_int($length));

            if ($oidPrefix !== null && $oidPrefix !== substr($oid, 0, $length)) {
                throw new SnmpFailed('Requested OID tree is not a first degree indexed SNMP value');
            }

            $oidPrefix = substr($oid, 0, $length);

            yield substr($oid, $length + 1) => $value;
        }
    }

    /**
     * @return mixed[]
     */
    public function walk(string $oidString) : iterable
    {
        $walk = $this->client->walk($oidString);

        while ($walk->hasOids()) {
            $oid   = $walk->next();
            $value = $oid->getValue();

            yield $oid->getOid() => $value === null ? null : $value->getValue();
        }
    }
}
