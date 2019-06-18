<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Helper;

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\ArrayType\KeyValuePair;

class TypeMapper
{
    /**
     * @param iterable<string, string> $haystack
     *
     * @return array<string, float>
     */
    public static function stringsToFloats(iterable $haystack) : array
    {
        return ArrayType::mapByCallback(
            $haystack,
            static function (KeyValuePair $keyValuePair) : KeyValuePair {
                return new KeyValuePair($keyValuePair->getKey(), (float) $keyValuePair->getValue());
            }
        );
    }
}
