PHP SNMP Client
===============

[![Build Status](https://github.com/simPod/PHP-SNMP/workflows/CI/badge.svg)](https://github.com/simPod/PHP-SNMP/actions?query=workflow%3A%22CI%22)
[![Packagist](https://poser.pugx.org/simpod/php-snmp/v/stable.svg)](https://packagist.org/packages/simpod/php-snmp)
[![Code Coverage][Coverage image]][CodeCov Master]

## Requirements

PHP 8.5 or later in the PHP 8 series.

## Direct SNMP with typed values

Use `ExtensionSnmpClient` with `ext-snmp` to query a device directly. Pass
`typedValues: true` to receive `SnmpValue` objects keyed by numeric OID from
GET, GETNEXT, walks, and batches. The default output is unchanged.

```php
use SimPod\PhpSnmp\Transport\ExtensionSnmpClient;

$client = new ExtensionSnmpClient(host: '192.0.2.1', community: $community, typedValues: true);
$value = $client->get(['.1.3.6.1.2.1.1.1.0'])['.1.3.6.1.2.1.1.1.0'];
// $value->type is the ASN.1 type tag; $value->value contains the unquoted system description.
```

Octet strings contain raw bytes, including any NUL bytes. Integer types,
including Counter64, contain decimal strings, so no precision is lost by
casting to a PHP integer. ASN.1 NULL has a `null` payload. The client returns
the extension's plain data without applying MIB display hints.
If you set the `timeoutMs` argument, pass **microseconds** despite its name.
In typed mode, if Net-SNMP reports a missing walk root, the client uses GETNEXT
to distinguish a missing subtree from the end of the MIB.

## Walk defaults

Configure `defaultMaxRepetitions` once on `ApiSnmpClient`, `CliSnmpClient`, or
`ExtensionSnmpClient`. It defaults to **20**. An omitted or `null` walk limit uses
the executing client's default; an explicit integer, including `20`, overrides it.

```php
use SimPod\PhpSnmp\Transport\ApiSnmpClient;
use SimPod\PhpSnmp\Transport\Request;

$client = new ApiSnmpClient(
    $httpClient,
    $requestFactory,
    $streamFactory,
    'http://snmp-proxy',
    host: '192.0.2.1',
    defaultMaxRepetitions: 5,
);

$client->walk($oid);     // Uses 5.
$client->walk($oid, 20); // Uses 20, not the configured default.
$client->batch([
    'interfaces' => Request::walk($interfacesOid), // Uses 5.
    'sensors' => Request::walk($sensorsOid, 15),   // Uses 15.
]);
```

`OidStripper::walk()` also inherits the client default. GET and GETNEXT do not use
max repetitions; the CLI transport continues to use ordinary walks for SNMP v1.
`FallbackSnmpClient` preserves an omitted limit so each attempted client applies
its own default. Configure the same default on each client for consistent retries.
Requests are not changed when a client applies its default, so a request can be
reused with another client.

### Migrating custom clients (breaking change)

Implementations of `SnmpClient` must accept
`walk(string $oid, int|null $maxRepetitions = null): array`. Resolve `null` to the
client default only when executing the walk. Decorators must preserve `null`
rather than replace it with `20` or cast it to `0`.

`Request::walk($oid)->maxRepetitions` is now `null`, not `20`. Consumers that read
this property directly must apply the client default when it is `null`. Existing
callers that omit the limit still use 20 on unconfigured clients.

[Coverage image]: https://codecov.io/gh/simPod/PHP-SNMP/branch/master/graph/badge.svg
[CodeCov Master]: https://codecov.io/gh/simPod/PHP-SNMP/branch/master
