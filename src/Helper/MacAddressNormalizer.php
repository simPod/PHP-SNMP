<?php

declare(strict_types=1);

namespace SimPod\PhpSnmp\Helper;

use function bin2hex;
use function strlen;

class MacAddressNormalizer
{
    public static function normalize(iterable $macAddresses) : iterable
    {
        foreach ($macAddresses as $key => $macAddress) {
            if (strlen($macAddress) === 6) {
                $macAddress = bin2hex($macAddress);
                $formattedMacAddress = '';
                for ($i = 0; $i <= 12; $i++) {
                    $formattedMacAddress .= $macAddress[$i];
                    if ($i % 2 === 1 && $i !== 11) {
                        $formattedMacAddress .= ':';
                    }
                }

                yield $key => $formattedMacAddress;

                continue;
            }

            // @todo move all normalization thingies from our repo here
            yield $key => $macAddress;
        }
    }
}
