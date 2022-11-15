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

class Transaction
{
    public const WITHDRAW = 'withdraw';

    public const DEPOSIT = 'deposit';

    public const USD = 'USD';

    public const JPY = 'JPY';

    public function __construct(private string $type, private string $date, private string $currency, private string $amount, private Client $client)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function isDeposit(): bool
    {
        return self::DEPOSIT === $this->getType();
    }

    public function isWithdraw(): bool
    {
        return self::WITHDRAW === $this->getType();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function isCurrencyUSD(): bool
    {
        return self::USD === $this->currency;
    }

    public function isCurrencyJPY(): bool
    {
        return self::JPY === $this->currency;
    }
}
