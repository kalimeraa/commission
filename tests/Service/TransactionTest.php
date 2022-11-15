<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Kalimeraa\Commission\Tests\Service;

use Kalimeraa\Commission\Service\Client;
use Kalimeraa\Commission\Service\Transaction;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class TransactionTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @covers ::isWithdraw
     */
    public function testTransactionShouldBeWithdraw(): void
    {
        $transaction = new Transaction('withdraw', '2016-01-10', 'USD', '23.23', $this->client);

        static::assertTrue($transaction->isWithdraw());
    }

    /**
     * @covers ::isDeposit
     */
    public function testTransactionShouldBeDeposit(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'USD', '23.23', $this->client);

        static::assertTrue($transaction->isDeposit());
    }

    /**
     * @covers ::isCurrencyUSD
     */
    public function testTransactionCurrencyShouldBeUSD(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'USD', '23.23', $this->client);

        static::assertTrue($transaction->isCurrencyUSD());
    }

    /**
     * @covers ::isCurrencyJPY
     */
    public function testTransactionCurrencyShouldBeJPY(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertTrue($transaction->isCurrencyJPY());
    }

    /**
     * @covers ::getClient
     */
    public function testShouldGotClientFromTransaction(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertSame($this->client, $transaction->getClient());
    }

    /**
     * @covers ::getDate
     */
    public function testShouldGotTransactionDate(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertSame('2016-01-10', $transaction->getDate());
    }

    /**
     * @covers ::getCurrency
     */
    public function testShouldGotTransactionCurrency(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertSame('JPY', $transaction->getCurrency());
    }

    /**
     * @covers ::getAmount
     */
    public function testShouldGotTransactionAmount(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertSame('23.23', $transaction->getAmount());
    }

    /**
     * @covers ::getType
     */
    public function testShouldGotTransactionType(): void
    {
        $transaction = new Transaction('deposit', '2016-01-10', 'JPY', '23.23', $this->client);

        static::assertSame('deposit', $transaction->getType());
    }
}
