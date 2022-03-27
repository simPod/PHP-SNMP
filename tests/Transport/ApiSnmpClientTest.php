<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Transport;

use Exception;
use Http\Client\Curl\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use SimPod\PhpSnmp\Exception\EndOfMibReached;
use SimPod\PhpSnmp\Exception\GeneralException;
use SimPod\PhpSnmp\Exception\NoRequestsProvided;
use SimPod\PhpSnmp\Exception\NoSuchInstanceExists;
use SimPod\PhpSnmp\Exception\NoSuchObjectExists;
use SimPod\PhpSnmp\Exception\TimeoutReached;
use SimPod\PhpSnmp\Tests\BaseTestCase;
use SimPod\PhpSnmp\Transport\ApiSnmpClient;
use SimPod\PhpSnmp\Transport\Request;

use function Safe\json_decode;
use function Safe\json_encode;
use function sprintf;

use const JSON_BIGINT_AS_STRING;

final class ApiSnmpClientTest extends BaseTestCase
{
    /** @var Client&MockObject */
    private $client;

    public function testGet(): void
    {
        $apiSnmp = $this->createApiSnmp();

        $response = <<<'JSON'
{
    "result": [
        [
            ".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.1.2",
            ".1.3.6.1.2.1.25.2.3.1.2.4", ".1.3.6.1.2.1.25.2.1.9"
        ]
    ]
}
JSON;
        $this->client->method('sendRequest')
            ->with(
                self::callback(
                    static function (RequestInterface $request): bool {
                        $json = <<<'JSON'
{
    "host": "127.0.0.1",
    "community": "public",
    "version": "2c",
    "timeout": 1,
    "retries": 3,
    "requests": [
        {
            "request_type": "get",
            "oids": [".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.3.1.2.4"]
        }
    ]
}
JSON;

                        return (string) $request->getUri() === 'http://localhost/snmp-proxy'
                            && self::jsonIsIdentical($json, (string) $request->getBody());
                    }
                )
            )
            ->willReturn(new Response(200, [], $response));

        $result = $apiSnmp->get(['.1.3.6.1.2.1.25.2.3.1.2.1', '.1.3.6.1.2.1.25.2.3.1.2.4']);

        self::assertSame(
            [
                '.1.3.6.1.2.1.25.2.3.1.2.1' => '.1.3.6.1.2.1.25.2.1.2',
                '.1.3.6.1.2.1.25.2.3.1.2.4' => '.1.3.6.1.2.1.25.2.1.9',
            ],
            $result
        );
    }

    public function testGetNext(): void
    {
        $apiSnmp = $this->createApiSnmp();

        $response = <<<'JSON'
{
    "result": [
        [
            ".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.1.2",
            ".1.3.6.1.2.1.25.2.3.1.2.4", ".1.3.6.1.2.1.25.2.1.9"
        ]
    ]
}
JSON;
        $this->client->method('sendRequest')
            ->with(
                self::callback(
                    static function (RequestInterface $request): bool {
                        $json = <<<'JSON'
{
    "host": "127.0.0.1",
    "community": "public",
    "version": "2c",
    "timeout": 1,
    "retries": 3,
    "requests": [
        {
            "request_type": "getNext",
            "oids": [
                ".1.3.6.1.2.1.25.2.3.1.2",
                ".1.3.6.1.2.1.25.2.3.1.2.3"
            ]
        }
    ]
}
JSON;

                        return (string) $request->getUri() === 'http://localhost/snmp-proxy'
                            && self::jsonIsIdentical($json, (string) $request->getBody());
                    }
                )
            )
            ->willReturn(new Response(200, [], $response));

        $result = $apiSnmp->getNext(['.1.3.6.1.2.1.25.2.3.1.2', '.1.3.6.1.2.1.25.2.3.1.2.3']);

        self::assertSame(
            [
                '.1.3.6.1.2.1.25.2.3.1.2.1' => '.1.3.6.1.2.1.25.2.1.2',
                '.1.3.6.1.2.1.25.2.3.1.2.4' => '.1.3.6.1.2.1.25.2.1.9',
            ],
            $result
        );
    }

    public function testWalk(): void
    {
        $apiSnmp = $this->createApiSnmp();

        $response = <<<'JSON'
{
    "result": [
        [
            ".1.3.6.1.2.1.31.1.1.1.15.1000001", 100000,
            ".1.3.6.1.2.1.31.1.1.1.15.1000003", 60000,
            ".1.3.6.1.2.1.31.1.1.1.15.1000005", 80000
        ]
    ]
}
JSON;
        $this->client->method('sendRequest')
            ->with(
                self::callback(
                    static function (RequestInterface $request): bool {
                        $json = <<<'JSON'
{
    "host": "127.0.0.1",
    "community": "public",
    "version": "2c",
    "timeout": 1,
    "retries": 3,
    "requests": [
        {
            "request_type": "walk",
            "oids": [".1.3.6.1.2.1.31.1.1.1.15"],
            "max_repetitions": 20
        }
    ]
}
JSON;

                        return (string) $request->getUri() === 'http://localhost/snmp-proxy'
                            && self::jsonIsIdentical($json, (string) $request->getBody());
                    }
                )
            )
            ->willReturn(new Response(200, [], $response));

        $result = $apiSnmp->walk('.1.3.6.1.2.1.31.1.1.1.15');

        self::assertSame(
            [
                '.1.3.6.1.2.1.31.1.1.1.15.1000001' => 100000,
                '.1.3.6.1.2.1.31.1.1.1.15.1000003' => 60000,
                '.1.3.6.1.2.1.31.1.1.1.15.1000005' => 80000,
            ],
            $result
        );
    }

    public function testBatch(): void
    {
        $apiSnmp = $this->createApiSnmp();

        $response = <<<'JSON'
{
    "result": [
        [
            ".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.1.2",
            ".1.3.6.1.2.1.25.2.3.1.2.4", ".1.3.6.1.2.1.25.2.1.9"
        ],
        [
            ".1.3.6.1.2.1.31.1.1.1.15.1000001", 100000,
            ".1.3.6.1.2.1.31.1.1.1.15.1000003", 60000,
            ".1.3.6.1.2.1.31.1.1.1.15.1000005", 80000
        ],
        [
            ".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.1.2",
            ".1.3.6.1.2.1.25.2.3.1.2.4", ".1.3.6.1.2.1.25.2.1.9"
        ]
    ]
}
JSON;
        $this->client->method('sendRequest')
            ->with(
                self::callback(
                    static function (RequestInterface $request): bool {
                        $json = <<<'JSON'
{
    "host": "127.0.0.1",
    "community": "public",
    "version": "2c",
    "timeout": 1,
    "retries": 3,
    "requests": [
        {
            "request_type": "get",
            "oids": [".1.3.6.1.2.1.25.2.3.1.2.1", ".1.3.6.1.2.1.25.2.3.1.2.4"]
        },
        {
            "request_type": "walk",
            "oids": [".1.3.6.1.2.1.31.1.1.1.15"],
            "max_repetitions": 20
        },
        {
            "request_type": "getNext",
            "oids": [
                ".1.3.6.1.2.1.25.2.3.1.2",
                ".1.3.6.1.2.1.25.2.3.1.2.3"
            ]
        }
    ]
}
JSON;

                        return (string) $request->getUri() === 'http://localhost/snmp-proxy'
                            && self::jsonIsIdentical($json, (string) $request->getBody());
                    }
                )
            )
            ->willReturn(new Response(200, [], $response));

        $result = $apiSnmp->batch(
            [
                'get' => Request::get(['.1.3.6.1.2.1.25.2.3.1.2.1', '.1.3.6.1.2.1.25.2.3.1.2.4']),
                'walk' => Request::walk('.1.3.6.1.2.1.31.1.1.1.15'),
                'getNext' => Request::getNext(['.1.3.6.1.2.1.25.2.3.1.2', '.1.3.6.1.2.1.25.2.3.1.2.3']),
            ]
        );

        self::assertSame(
            [
                'get' => [
                    '.1.3.6.1.2.1.25.2.3.1.2.1' => '.1.3.6.1.2.1.25.2.1.2',
                    '.1.3.6.1.2.1.25.2.3.1.2.4' => '.1.3.6.1.2.1.25.2.1.9',
                ],
                'walk' => [
                    '.1.3.6.1.2.1.31.1.1.1.15.1000001' => 100000,
                    '.1.3.6.1.2.1.31.1.1.1.15.1000003' => 60000,
                    '.1.3.6.1.2.1.31.1.1.1.15.1000005' => 80000,
                ],
                'getNext' => [
                    '.1.3.6.1.2.1.25.2.3.1.2.1' => '.1.3.6.1.2.1.25.2.1.2',
                    '.1.3.6.1.2.1.25.2.3.1.2.4' => '.1.3.6.1.2.1.25.2.1.9',
                ],
            ],
            $result
        );
    }

    public function testBatchNoRequests(): void
    {
        $this->expectExceptionObject(NoRequestsProvided::new());

        $this->createApiSnmp()->batch([]);
    }

    public function testThatParametersAreCorrectlyPropagatedToTheJsonRequest(): void
    {
        $this->client = $this->createMock(Client::class);
        $psr17Factory = new Psr17Factory();

        $apiSnmp = new ApiSnmpClient(
            $this->client,
            $psr17Factory,
            $psr17Factory,
            'http://somewhere',
            'lorem',
            'ipsum',
            50,
            5,
            '1'
        );

        $response = <<<'JSON'
{
    "result": [
        [
            ".1.3.6.1.2.1.2.2.1.2.1000009","Port-Channel9"
        ]
    ]
}
JSON;
        $this->client->method('sendRequest')
            ->with(
                self::callback(
                    static function (RequestInterface $request): bool {
                        $json = <<<'JSON'
{
    "host": "lorem",
    "community": "ipsum",
    "version": "1",
    "timeout": 50,
    "retries": 5,
    "requests": [
        {
            "request_type": "get",
            "oids": [".1.3.6.1.2.1.2.2.1.2.1000009"]
        }
    ]
}
JSON;

                        return (string) $request->getUri() === 'http://somewhere/snmp-proxy'
                            && self::jsonIsIdentical($json, (string) $request->getBody());
                    }
                )
            )
            ->willReturn(new Response(200, [], $response));

        $result = $apiSnmp->get(['.1.3.6.1.2.1.2.2.1.2.1000009']);

        self::assertSame(['.1.3.6.1.2.1.2.2.1.2.1000009' => 'Port-Channel9'], $result);
    }

    public function testErrorJsonDecodingResponse(): void
    {
        $this->client = $this->createMock(Client::class);
        $psr17Factory = new Psr17Factory();

        $apiSnmp = new ApiSnmpClient(
            $this->client,
            $psr17Factory,
            $psr17Factory,
            'http://somewhere',
            'lorem',
            'ipsum',
            50,
            5,
            '1'
        );

        $response = '{wow this is not a valid json response';
        $this->client->method('sendRequest')->willReturn(new Response(500, [], $response));

        self::assertSnmpException(
            GeneralException::new(
                sprintf('Response is not valid JSON [HTTP 500]: "%s"', $response),
                null,
                'lorem',
                ['.1.3.6']
            ),
            static function () use ($apiSnmp): void {
                $apiSnmp->get(['.1.3.6']);
            }
        );
    }

    public function testErrorUnexpectedStatusCodeResponse(): void
    {
        $this->client = $this->createMock(Client::class);
        $psr17Factory = new Psr17Factory();

        $apiSnmp = new ApiSnmpClient(
            $this->client,
            $psr17Factory,
            $psr17Factory,
            'http://somewhere',
            'lorem',
            'ipsum',
            50,
            5,
            '1'
        );

        $response = '{"wow": "this server responds with JSON"}';
        $this->client->method('sendRequest')->willReturn(new Response(500, [], $response));

        $error = sprintf('Unexpected HTTP status code: 500, response: "%s"', $response);
        self::assertSnmpException(
            GeneralException::new($error, null, 'lorem', ['.1.3.6']),
            static function () use ($apiSnmp): void {
                $apiSnmp->get(['.1.3.6']);
            }
        );
    }

    public function testWalkWithEndOfMibError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willReturn(new Response(500, [], '{"error": "end of mib: .1.15"}'));

        self::assertSnmpException(
            EndOfMibReached::fromOid('127.0.0.1', '.1.15'),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.15');
            }
        );
    }

    public function testWalkWithNoSuchInstanceError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willReturn(new Response(500, [], '{"error": "no such instance: .1.3.6.1.2.1.1.1"}'));

        self::assertSnmpException(
            NoSuchInstanceExists::fromOid('127.0.0.1', '.1.3.6.1.2.1.1.1'),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.3.6.1.2.1.1.1');
            }
        );
    }

    public function testWalkWithNoSuchObjectError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willReturn(new Response(500, [], '{"error": "no such object: .1.4"}'));

        self::assertSnmpException(
            NoSuchObjectExists::fromOid('127.0.0.1', '.1.4'),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.4');
            }
        );
    }

    public function testWalkWithTimeoutError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willReturn(new Response(500, [], '{"error": "timeout: .1.4"}'));

        self::assertSnmpException(
            TimeoutReached::fromOid('127.0.0.1', '.1.4'),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.4');
            }
        );
    }

    public function testWalkWithRequestError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willThrowException(new Exception('some error'));

        self::assertSnmpException(
            GeneralException::new('some error', null, '127.0.0.1', ['.1.4']),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.4');
            }
        );
    }

    public function testWalkWithUnexpectedError(): void
    {
        $apiSnmp = $this->createApiSnmp();
        $this->client->method('sendRequest')
            ->willReturn(new Response(500, [], '{"error": "something unexpected happened"}'));

        self::assertSnmpException(
            GeneralException::new('something unexpected happened', null, '127.0.0.1', ['.1.4']),
            static function () use ($apiSnmp): void {
                $apiSnmp->walk('.1.4');
            }
        );
    }

    private static function jsonIsIdentical(string $expected, string $actual): bool
    {
        return json_encode(json_decode($expected, true, 5, JSON_BIGINT_AS_STRING)) === $actual;
    }

    private function createApiSnmp(): ApiSnmpClient
    {
        $this->client = $this->createMock(Client::class);
        $psr17Factory = new Psr17Factory();

        return new ApiSnmpClient($this->client, $psr17Factory, $psr17Factory, 'http://localhost');
    }
}
