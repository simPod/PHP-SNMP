<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Transport;

use SimPod\PhpSnmp\Exception\SnmpApiError;
use function array_key_exists;
use function assert;
use function base64_decode;
use function curl_close;
use function curl_error;
use function curl_init;
use function is_array;
use function is_bool;
use function json_decode;
use function json_last_error_msg;
use function strtr;
use const CURLOPT_CONNECTTIMEOUT;
use const CURLOPT_HEADER;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_TIMEOUT;
use function var_dump;

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
    private $retry;

    /** @var int */
    private $timeout;

    public function __construct(
        string $apiHostUrl,
        string $community = 'public',
        string $host = '127.0.0.1',
        int $timeout = 30,
        int $retry = 3
    ) {
        $this->apiHostUrl = $apiHostUrl;
        $this->community = $community;
        $this->host = $host;
        $this->timeout = $timeout;

        assert($retry >= 0);
        $this->retry = $retry;
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walkFirstDegree(string $oid) : iterable
    {
        yield from $this->get($oid, true);
    }

    /**
     * @return iterable<string, mixed>
     */
    public function walk(string $oid) : iterable
    {
        yield from $this->get($oid, false);
    }

    private function get(string $oid, bool $stripOidPrefix) : iterable
    {
        for ($i = 0; $i <= $this->retry; $i++) {
            try {
                return yield from $this->doGet($oid, $stripOidPrefix);
            } catch (SnmpApiError $error) {
            }
        }

        assert(isset($error));

        throw SnmpApiError::tooManyRetries($error);
    }

    private function doGet(string $oid, bool $stripOidPrefix) : iterable
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
        $error = curl_error($curl);
        curl_close($curl);

        if (is_bool($result)) {
            throw SnmpApiError::connectionFailed($error);
        }

        $json = json_decode($result, true);
        if ($json === null) {
            throw SnmpApiError::invalidJson(json_last_error_msg());
        }

        if (array_key_exists('error', $json)) {
            throw SnmpApiError::failed($json['error']);
        }

        if (!array_key_exists('response', $json)) {
            return [];
        }


        foreach ($json['response'] as $key => $value) {
            if (is_array($value) && $value['type'] === 'string') {
                $data = base64_decode($value['value'], true);
                if (is_bool($data)) {
                    throw SnmpApiError::invalidBase64String($value['value']);
                }

                yield $key => $data;
                continue;
            }

            yield $key => $value;
        }
    }
}
