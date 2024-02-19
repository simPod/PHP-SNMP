<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Helpers;

use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Transport\Request;
use SimPod\PhpSnmp\Transport\SnmpClient;

use function array_key_first;
use function strlen;
use function strpos;
use function strrpos;
use function substr;

final class OidStripper
{
    /**
     * @param array<string, mixed> $leafOidData
     * @psalm-param array<string, T> $leafOidData
     *
     * @return array<int, mixed>
     * @psalm-return array<int, T>
     *
     * @psalm-template T
     */
    public static function stripParent(array $leafOidData): array
    {
        return self::batchStripParent([$leafOidData])[0];
    }

    /**
     * @param list<array<string, mixed>> $leafOidDataResponses
     * @psalm-param list<array<string, T>> $leafOidDataResponses
     *
     * @return list<array<int, mixed>>
     * @psalm-return list<array<int, T>>
     *
     * @psalm-template T
     */
    public static function batchStripParent(array $leafOidDataResponses): array
    {
        $result = [];
        foreach ($leafOidDataResponses as $leafOidData) {
            $firstKey = array_key_first($leafOidData);
            if ($firstKey === null) {
                throw GeneralException::new('Expected non-empty array');
            }

            $lastDotPos = strrpos($firstKey, '.');
            if ($lastDotPos === false) {
                throw GeneralException::new('Expected keys to be full OIDs');
            }

            $stripLength = $lastDotPos + 1;

            $responseResult = [];
            foreach ($leafOidData as $oid => $value) {
                $responseResult[(int) substr($oid, $stripLength)] = $value;
            }

            $result[] = $responseResult;
        }

        return $result;
    }

    /** @return array<string, mixed> */
    public static function walk(SnmpClient $snmpClient, string $oid, int $maxRepetitions = 20): array
    {
        return self::batchStripParentPrefix($snmpClient, [Request::walk($oid, $maxRepetitions)])[0];
    }

    /**
     * @param array<mixed, Request> $requests
     * @psalm-param array<T, Request> $requests
     *
     * @return array<mixed, array<mixed>>
     * @psalm-return array<T, array<mixed>>
     *
     * @psalm-template T
     */
    public static function batchStripParentPrefix(SnmpClient $snmpClient, array $requests): array
    {
        $responses = $snmpClient->batch($requests);

        $result = [];
        foreach ($requests as $requestKey => $request) {
            switch ($request->type) {
                case Request::Get:
                    $i = 0;
                    $getResult = [];

                    foreach ($responses[$requestKey] as $childOid => $value) {
                        $getResult[substr($childOid, strlen($request->oids[$i]) + 1)] = $value;
                        $i++;
                    }

                    $result[$requestKey] = $getResult;

                    break;
                case Request::GetNext:
                    $i = 0;
                    $getNextResult = [];

                    foreach ($responses[$requestKey] as $childOid => $value) {
                        if (strpos($childOid, $request->oids[$i]) === 0) {
                            $childOid = substr($childOid, strlen($request->oids[$i]) + 1);
                        }

                        $getNextResult[$childOid] = $value;
                        $i++;
                    }

                    $result[$requestKey] = $getNextResult;

                    break;
                case Request::Walk:
                    $stripLength = strlen($request->oids[0]) + 1;

                    $walkResult = [];
                    foreach ($responses[$requestKey] as $childOid => $value) {
                        $walkResult[substr($childOid, $stripLength)] = $value;
                    }

                    $result[$requestKey] = $walkResult;
            }
        }

        return $result;
    }
}
