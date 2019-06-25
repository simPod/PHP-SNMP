<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SimPod\PhpSnmp\Exception\SnmpApiError;
use SimPod\PhpSnmp\Transport\ApiSnmp;
use function base64_encode;
use function iterable_to_array;
use function json_encode;

final class ApiSnmpTest extends TestCase
{
    /**
     * @dataProvider providerWalk
     */
    public function testWalk(array $response, array $expectedResult) : void
    {
        $responseQueue = [
            static function (Request $request) use ($response) : Response {
                $expectedUri = 'http://some-host/snmp/127.0.0.1/public/.1.2.3.4.5?strip=1';
                self::assertSame($expectedUri, (string) $request->getUri());

                return new Response(200, [], (string) json_encode($response));
            },
        ];

        $result = $this->getSnmp($responseQueue)->walk('.1.2.3.4.5');

        self::assertSame($expectedResult, iterable_to_array($result));
    }

    public function providerWalk() : iterable
    {
        yield [[], []];
        yield [
            [
                'response' => [
                    1 => 123,
                    5 => [
                        'type' => 'string',
                        'value' => base64_encode('some string'),
                    ],
                    12 => 5.32,
                ],
            ],
            [1 => 123, 5 => 'some string', 12 => 5.32],
        ];
    }

    /**
     * @param string $response
     * @param string $error
     *
     * @dataProvider providerWalkWithError
     */
    public function testWalkWithError(string $response, string $error) : void
    {
        $responseQueue = [
            new Response(200, [], $response),
        ];

        $this->expectException(SnmpApiError::class);
        $this->expectExceptionMessage($error);

        iterable_to_array($this->getSnmp($responseQueue)->walk('.1.2.3.4.5'));
    }

    public function providerWalkWithError() : iterable
    {
        yield 'general error' => ['{"error": "something bad happened"}', 'something bad happened'];
        yield 'malformed JSON' => ['{malformed', 'Failed to decode JSON'];
        yield 'invalid base64' => ['{"response": [{"type": "string", "value": "meh=="}]}', 'base64_decode() failed '];
    }

    /**
     * @dataProvider providerWalkWithCompleteOids
     */
    public function testWalkWithCompleteOids(array $response, array $expectedResult) : void
    {
        $responseQueue = [
            static function (Request $request) use ($response) : Response {
                $expectedUri = 'http://some-host/snmp/127.0.0.1/public/.1.2.3.4.5';
                self::assertSame($expectedUri, (string) $request->getUri());

                return new Response(200, [], (string) json_encode($response));
            },
        ];

        $result = $this->getSnmp($responseQueue)->walkWithCompleteOids('.1.2.3.4.5');

        self::assertSame($expectedResult, iterable_to_array($result));
    }

    public function providerWalkWithCompleteOids() : iterable
    {
        yield [[], []];
        yield [
            [
                'response' => [
                    '.1.2.3.4.5.1' => 123,
                    '.1.2.3.4.5.5' => [
                        'type' => 'string',
                        'value' => base64_encode('some string'),
                    ],
                    '.1.2.3.4.5.12' => 5.32,
                ],
            ],
            ['.1.2.3.4.5.1' => 123, '.1.2.3.4.5.5' => 'some string', '.1.2.3.4.5.12' => 5.32],
        ];
    }

    /**
     * @param array<callable|Response> $responseQueue
     */
    private function getSnmp(array $responseQueue) : ApiSnmp
    {
        $guzzle = new Client(['handler' => new MockHandler($responseQueue)]);

        return new ApiSnmp($guzzle, 'http://some-host');
    }
}
