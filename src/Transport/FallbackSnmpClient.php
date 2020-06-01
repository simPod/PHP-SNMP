<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use Psr\Log\LoggerInterface;
use SimPod\PhpSnmp\Exception\GeneralException;

final class FallbackSnmpClient implements SnmpClient
{
    /** @var LoggerInterface */
    private $logger;

    /** @var iterable<SnmpClient> */
    private $snmpClients;

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
    public function walk(string $oid, int $maxRepetitions = 40) : array
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
        // @phpstan-ignore-next-line some bug in phpstan for the moment, but we know the types are correct...
        return $this->tryClients(
            static function (SnmpClient $client) use ($requests) : array {
                return $client->batch($requests);
            }
        );
    }

    /**
     * @param callable(SnmpClient): array<mixed> $requestCallback
     *
     * @return array<mixed>
     */
    private function tryClients(callable $requestCallback) : array
    {
        foreach ($this->snmpClients as $key => $snmpClient) {
            try {
                return $requestCallback($snmpClient);
            } catch (GeneralException $exception) {
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
