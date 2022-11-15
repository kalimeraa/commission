<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kalimeraa\Commission\Service;

use GuzzleHttp\Client;

class ExchangeRate
{
    public static function eurUSD(string $dollar)
    {
        $exhangeRates = self::fetchExchangeRates();

        return bcdiv($dollar, (string) $exhangeRates['rates']['USD'], 2);
    }

    public static function eurJPY(string $jpy)
    {
        $exhangeRates = self::fetchExchangeRates();

        return bcdiv($jpy, (string) $exhangeRates['rates']['JPY'], 2);
    }

    private static function fetchExchangeRates(): array
    {
        $client = new Client();
        $response = $client->get('https://developers.paysera.com/tasks/api/currency-exchange-rates');
        $body = (string) $response->getBody();

        return json_decode($body, true);
    }
}
