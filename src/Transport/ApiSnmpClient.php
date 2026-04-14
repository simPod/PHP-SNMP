<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use JsonException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SimPod\PhpSnmp\Exception\EndOfMibReached;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\NoRequestsProvided;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Exception\NoSuchObjectExists;
use SimPod\PhpSnmp\Exception\TimeoutReached;
use Throwable;

use function array_key_exists;
use function array_keys;
use function array_map;
use function array_values;
use function count;
use function json_decode;
use function Psl\Json\encode;
use function Psl\Regex\first_match;
use function sprintf;

use const JSON_BIGINT_AS_STRING;
use const JSON_THROW_ON_ERROR;

final class ApiSnmpClient implements SnmpClient
{
    private const ApiPath = '/snmp-proxy';

    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private string $apiHostUrl,
        private string $host = '127.0.0.1',
        private string $community = 'public',
        private int $timeout = 1,
        private int $retries = 3,
        private string $version = '2c',
    ) {
    }

    /** @inheritDoc */
    public function get(array $oids): array
    {
        return $this->batch([Request::get($oids)])[0];
    }

    /** @inheritDoc */
    public function getNext(array $oids): array
    {
        return $this->batch([Request::getNext($oids)])[0];
    }

    /** @inheritDoc */
    public function walk(string $oid, int $maxRepetitions = 20): array
    {
        return $this->batch([Request::walk($oid, $maxRepetitions)])[0];
    }

    /** @inheritDoc */
    public function batch(array $requests): array
    {
        if ($requests === []) {
            throw NoRequestsProvided::new();
        }

        $requestParameters = [
            'host' => $this->host,
            'community' => $this->community,
            'version' => $this->version,
            'timeout' => $this->timeout,
            'retries' => $this->retries,
            'requests' => array_map(
                static function (Request $request): array {
                    $requestArray = [
                        'request_type' => $request->type,
                        'oids' => $request->oids,
                    ];

                    if ($request->maxRepetitions !== null) {
                        $requestArray['max_repetitions'] = $request->maxRepetitions;
                    }

                    return $requestArray;
                },
                array_values($requests),
            ),
        ];

        $requestKeys = array_keys($requests);
        $result = [];

        foreach ($this->doExecuteRequest($requestParameters) as $requestNo => $rawRequestResult) {
            $requestResult = [];
            for ($i = 0, $iMax = count($rawRequestResult); $i < $iMax; $i += 2) {
                $key = $rawRequestResult[$i];
                $value = $rawRequestResult[$i + 1];

                $requestResult[$key] = $value;
            }

            $result[$requestKeys[$requestNo]] = $requestResult;
        }

        return $result;
    }

    /**
     * @param array{requests: iterable<array{oids: iterable<string>}>} $requestParameters
     *
     * @return list<list<string>>
     */
    private function doExecuteRequest(array $requestParameters): array
    {
        $request = $this->requestFactory->createRequest('POST', $this->apiHostUrl . self::ApiPath)
            ->withBody($this->streamFactory->createStream(encode($requestParameters)));

        try {
            $response = $this->client->sendRequest($request);
        } catch (Throwable $throwable) {
            throw GeneralException::fromThrowable($throwable, $this->host, $this->getRequestsOids($requestParameters));
        }

        try {
            /** @var array{error: string}|array{result: list<list<string>>} $result */
            $result = json_decode((string) $response->getBody(), true, 5, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
        } catch (JsonException $throwable) {
            $error = sprintf(
                'Response is not valid JSON [HTTP %d]: "%s"',
                $response->getStatusCode(),
                (string) $response->getBody(),
            );

            throw GeneralException::new($error, $throwable, $this->host, $this->getRequestsOids($requestParameters));
        }

        if (array_key_exists('error', $result)) {
            $matches = first_match($result['error'], '~no such object: (.+)~');
            if ($matches !== null) {
                throw NoSuchObjectExists::fromOid($this->host, $matches[1]);
            }

            $matches = first_match($result['error'], '~no such instance: (.+)~');
            if ($matches !== null) {
                throw NoSuchInstanceExists::fromOid($this->host, $matches[1]);
            }

            $matches = first_match($result['error'], '~end of mib: (.+)~');
            if ($matches !== null) {
                throw EndOfMibReached::fromOid($this->host, $matches[1]);
            }

            $matches = first_match($result['error'], '~timeout: (.+)~');
            if ($matches !== null) {
                throw TimeoutReached::fromOid($this->host, $matches[1]);
            }

            throw GeneralException::new(
                $result['error'],
                null,
                $this->host,
                $this->getRequestsOids($requestParameters),
            );
        }

        if ($response->getStatusCode() !== 200) {
            $error = sprintf(
                'Unexpected HTTP status code: %d, response: "%s"',
                $response->getStatusCode(),
                (string) $response->getBody(),
            );

            throw GeneralException::new($error, null, $this->host, $this->getRequestsOids($requestParameters));
        }

        return $result['result'];
    }

    /**
     * @param array{requests: iterable<array{oids: iterable<string>}>} $requestParameters
     *
     * @return list<string>
     */
    private function getRequestsOids(array $requestParameters): array
    {
        $oids = [];
        foreach ($requestParameters['requests'] as $request) {
            foreach ($request['oids'] as $oid) {
                $oids[] = $oid;
            }
        }

        return $oids;
    }
}
