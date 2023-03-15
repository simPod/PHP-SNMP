<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use Psr\Log\LoggerInterface;
use ReflectionClass;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\TimeoutReached;

final class FallbackSnmpClient implements SnmpClient
{
    /** @param iterable<SnmpClient> $snmpClients */
    public function __construct(private LoggerInterface $logger, private iterable $snmpClients)
    {
        if ($snmpClients === []) {
            throw GeneralException::new('No SNMP clients provided');
        }
    }

    /** @inheritDoc */
    public function get(array $oids): array
    {
        return $this->tryClients(
            static fn (SnmpClient $client): array => $client->get($oids)
        );
    }

    /** @inheritDoc */
    public function getNext(array $oids): array
    {
        return $this->tryClients(
            static fn (SnmpClient $client): array => $client->getNext($oids)
        );
    }

    /** @inheritDoc */
    public function walk(string $oid, int $maxRepetitions = 20): array
    {
        return $this->tryClients(
            static fn (SnmpClient $client): array => $client->walk($oid, $maxRepetitions)
        );
    }

    /** @inheritDoc */
    public function batch(array $requests): array
    {
        return $this->tryClients(
        /**
         * @return array<T, array<string, mixed>>
         *
         * @template T
         */
            static fn (SnmpClient $client): array => $client->batch($requests)
        );
    }

    /**
     * @param callable(SnmpClient): T $requestCallback
     *
     * @return T
     *
     * @template T of array
     */
    private function tryClients(callable $requestCallback): array
    {
        foreach ($this->snmpClients as $key => $snmpClient) {
            try {
                return $requestCallback($snmpClient);
            } catch (GeneralException | TimeoutReached $exception) {
                $reflection = new ReflectionClass($snmpClient);
                $this->logger->warning(
                    'SNMP request failed',
                    [
                        'clientKey' => $key,
                        'client' => $reflection->getShortName(),
                        'exception' => $exception,
                    ],
                );
            }
        }

        /** @phpstan-ignore-next-line $exception will always be there */
        throw GeneralException::new('All SNMP clients failed, last error: ' . $exception->getMessage(), $exception);
    }
}
