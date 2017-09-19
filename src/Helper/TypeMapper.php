<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Helper;

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\ArrayType\KeyValuePair;

class TypeMapper
{
    /**
     * @param string[] $haystack
     *
     * @return float[]
     */
    public static function stringsToFloats(array $haystack) : array
    {
        return ArrayType::mapByCallback(
            $haystack,
            static function (KeyValuePair $keyValuePair) : KeyValuePair {
                return new KeyValuePair($keyValuePair->getKey(), (float) $keyValuePair->getValue());
            }
        );
    }
}
