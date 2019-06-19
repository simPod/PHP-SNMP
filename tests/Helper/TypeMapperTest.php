<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Tests\Helper;

use PHPStan\Testing\TestCase;
use SimPod\PhpSnmp\Helper\TypeMapper;
use function iterable_to_array;

final class TypeMapperTest extends TestCase
{
    /**
     * @param string[] $strings
     * @param float[] $expected
     *
     * @dataProvider providerStringsToFloats
     */
    public function testStringsToFloats(array $strings, array $expected) : void
    {
        self::assertSame($expected, iterable_to_array(TypeMapper::stringsToFloats($strings)));
    }

    /**
     * @return iterable<array<string|null>>
     */
    public function providerStringsToFloats() : iterable
    {
        yield [
            ['1234', '#keysMatter' => '12345.6789', '0', '0.0', '12345.0', '0.12345'],
            [1234.0, '#keysMatter' => 12345.6789, 0.0, 0.0, 12345.0, 0.12345],
        ];
    }
}
