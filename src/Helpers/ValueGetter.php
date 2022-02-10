<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Helpers;

use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Transport\SnmpClient;

use function array_shift;
use function array_values;
use function strpos;

final class ValueGetter
{
    /**
     * @param array<int|string, mixed> $response
     * @psalm-param array<int|string, T> $response
     *
     * @psalm-return T
     *
     * @psalm-template T
     */
    public static function first(array $response): mixed
    {
        $result = array_shift($response);
        if ($result === null) {
            throw GeneralException::new('Expected non-empty array');
        }

        return $result;
    }

    public static function firstFromSameTree(SnmpClient $snmpClient, string $oid): mixed
    {
        return self::firstFromSameTrees($snmpClient, [$oid])[0];
    }

    /**
     * @param list<string> $oids
     *
     * @return list<mixed>
     */
    public static function firstFromSameTrees(SnmpClient $snmpClient, array $oids): array
    {
        $result = $snmpClient->getNext($oids);

        $i = 0;
        foreach ($result as $oid => $value) {
            if (strpos($oid, $oids[$i]) !== 0) {
                throw NoSuchInstanceExists::fromOid('', $oids[$i]);
            }

            $i++;
        }

        return array_values($result);
    }
}
