<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use Psr\Log\LoggerInterface;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\TimeoutReached;

final class FallbackSnmpClient implements SnmpClient
{
    private LoggerInterface $logger;

    /** @var iterable<SnmpClient> */
    private iterable $snmpClients;

    /** @param iterable<SnmpClient> $snmpClients */
    public function __construct(LoggerInterface $logger, iterable $snmpClients)
    {
        if ($snmpClients === []) {
            throw GeneralException::new('No SNMP clients provided');
        }

        $this->logger      = $logger;
        $this->snmpClients = $snmpClients;
    }

    /** @inheritDoc */
    public function get(array $oids) : array
    {
        return $this->tryClients(
            static function (SnmpClient $client) use ($oids) : array {
                return $client->get($oids);
            }
        );
    }

    /** @inheritDoc */
    public function getNext(array $oids) : array
    {
        return $this->tryClients(
            static function (SnmpClient $client) use ($oids) : array {
                return $client->getNext($oids);
            }
        );
    }

    /** @inheritDoc */
    public function walk(string $oid, int $maxRepetitions = 20) : array
    {
        return $this->tryClients(
            static function (SnmpClient $client) use ($oid, $maxRepetitions) : array {
                return $client->walk($oid, $maxRepetitions);
            }
        );
    }

    /** @inheritDoc */
    public function batch(array $requests) : array
    {
        return $this->tryClients(
        /**
         * @return array<T, array<string, mixed>>
         *
         * @template T
         */
            static function (SnmpClient $client) use ($requests) : array {
                return $client->batch($requests);
            }
        );
    }

    /**
     * @param callable(SnmpClient): array<T, mixed> $requestCallback
     *
     * @return array<T, array<string, mixed>>
     *
     * @template T
     */
    private function tryClients(callable $requestCallback) : array
    {
        foreach ($this->snmpClients as $key => $snmpClient) {
            try {
                return $requestCallback($snmpClient);
            } catch (GeneralException | TimeoutReached $exception) {
                $this->logger->warning(
                    'SNMP request failed',
                    [
                        'clientKey' => $key,
                        'client' => $snmpClient,
                        'exception' => $exception,
                    ]
                );
            }
        }

        /** @phpstan-ignore-next-line $exception will always be there */
        throw GeneralException::new('All SNMP clients failed, last error: ' . $exception->getMessage(), $exception);
    }
}
