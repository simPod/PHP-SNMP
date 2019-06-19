<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\SnmpApiError;
use function array_key_exists;
use function base64_decode;
use function curl_close;
use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function is_array;
use function is_bool;
use function json_decode;
use function json_last_error_msg;
use function strtr;
use const CURLOPT_CONNECTTIMEOUT;
use const CURLOPT_HEADER;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_TIMEOUT;
use const JSON_BIGINT_AS_STRING;

final class ApiSnmp implements Snmp
{
    private const API_PATH = '/snmp/{community}/{host}/{oid}';

    /** @var string */
    private $apiHostUrl;

    /** @var string */
    private $community;

    /** @var string */
    private $host;

    /** @var int */
    private $timeout;

    public function __construct(
        string $apiHostUrl,
        string $community = 'public',
        string $host = '127.0.0.1',
        int $timeout = 30
    ) {
        $this->apiHostUrl = $apiHostUrl;
        $this->community  = $community;
        $this->host       = $host;
        $this->timeout    = $timeout;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable
    {
        yield from $this->getResponse($oid, true);
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walkWithCompleteOids(string $oid) : iterable
    {
        yield from $this->getResponse($oid, false);
    }

    /**
     * @return iterable<string, mixed>
     */
    private function getResponse(string $oid, bool $stripOidPrefix) : iterable
    {
        $url = $this->apiHostUrl . strtr(
            self::API_PATH,
            [
                '{community}' => $this->community,
                '{host}' => $this->host,
                '{oid}' => $oid,
            ]
        );

        if ($stripOidPrefix) {
            $url .= '?strip=1';
        }

        $curl = curl_init($url);
        if (is_bool($curl)) {
            throw SnmpApiError::curlInitFailed($url);
        }

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        $error  = curl_error($curl);
        curl_close($curl);

        if (is_bool($result)) {
            throw SnmpApiError::connectionFailed($error);
        }

        $json = json_decode($result, true, 4, JSON_BIGINT_AS_STRING);
        if ($json === null) {
            throw SnmpApiError::invalidJson(json_last_error_msg());
        }

        if (array_key_exists('error', $json)) {
            throw SnmpApiError::failed($json['error']);
        }

        if (! array_key_exists('response', $json)) {
            return [];
        }

        foreach ($json['response'] as $key => $value) {
            if (is_array($value) && $value['type'] === 'string') {
                $data = base64_decode($value['value'], true);
                if (is_bool($data)) { // https://github.com/phpstan/phpstan/pull/1366
                    throw SnmpApiError::invalidBase64String($value['value']);
                }

                yield $key => $data;
                continue;
            }

            yield $key => $value;
        }
    }
}
