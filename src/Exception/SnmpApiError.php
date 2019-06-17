<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Exception;

use RuntimeException;
use SimPod\PhpSnmp\SnmpException;
use function sprintf;

final class SnmpApiError extends RuntimeException implements SnmpException
{
    public static function curlInitFailed(string $url) : self
    {
        return new self(sprintf('curl_init() failed for URL "%s"', $url));
    }

    public static function connectionFailed(string $error) : self
    {
        return new self(sprintf('Failed to connect to the SNMP API: %s', $error));
    }

    public static function invalidJson(string $error) : self
    {
        return new self(sprintf('Failed to decode JSON response: %s', $error));
    }

    public static function invalidBase64String(string $string) : self
    {
        return new self(sprintf('base64_decode() failed on string "%s"', $string));
    }

    public static function failed(string $error) : self
    {
        return new self(sprintf('Failed to fetch OID: %s', $error));
    }

    public static function tooManyRetries(self $previous) : self
    {
        return new self('Failed to fetch OID: too many retries', 0, $previous);
    }
}
