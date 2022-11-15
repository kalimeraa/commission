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

class Commission
{
    private const DEPOSIT_FEE = '0.03';

    private const PRIVATE_WITHDRAW_FEE = '0.3';

    private const BUSINESS_WITHDRAW_FEE = '0.5';

    private const LIMIT = 1000;

    private const PERCANTAGE = '100';

    private array $transactions = [];

    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function handle(): void
    {
        foreach ($this->transactions as $transactionId => $transaction) {
            $fee = $this->getFeeRate($transaction);

            $amount = $transaction->getAmount();

            $currentTransactionAmountInEuro = $this->getAmountInEuro($transaction);

            $previousTransactionTotal = '0';

            if ($transactionId > 0 && $transactionId < \count($this->transactions)) {
                $previousTransactions = $this->getPreviousTransactions(
                    $transaction->getClient()->getId(),
                    $transactionId
                );

                if (empty($previousTransactions)) {
                    if ($currentTransactionAmountInEuro > self::LIMIT) {
                        $result = $this->getCommission($amount, $fee);
                    } else {
                        if ($transaction->isDeposit() && $transaction->getClient()->isPrivate()) {
                            $result = $this->getCommission($amount, $fee);
                        } elseif ($transaction->getClient()->isBusiness()) {
                            $result = $this->getCommission($amount, $fee);
                        } else {
                            $result = '0.00';
                        }
                    }
                }

                foreach ($previousTransactions as $previousTransaction) {
                    $interval = (new \DateTime($previousTransaction->getDate()))->diff(new \DateTime($transaction->getDate()));
                    $afterDay = (int) $interval->format('%R%a');

                    $previousTransactionAmountInEuro = $this->getAmountInEuro($previousTransaction);

                    $previousTransactionTotal = bcadd($previousTransactionAmountInEuro, $currentTransactionAmountInEuro, 2);
                    // check dates are in same week
                    if ($afterDay < 7) {
                        // if previous transactions are exceeded limit and same week
                        if ($previousTransactionAmountInEuro > self::LIMIT) {
                            $result = $this->getCommission($currentTransactionAmountInEuro, $fee);
                        } elseif ($previousTransactionTotal > self::LIMIT) {
                            $result = $this->getCommission($previousTransactionTotal, $fee);
                        } else {
                            $result = $this->getCommission($currentTransactionAmountInEuro, $fee);
                        }
                    } else {
                        if ($amount > self::LIMIT) {
                            $result = $this->getCommission($amount, $fee);
                        } else {
                            $result = '0.00';
                        }
                    }
                }
            } else {
                $result = $this->getCommission($amount, $fee);
            }

            echo $result.PHP_EOL;
        }
    }

    private function getCommission(string $amount, string $fee): string
    {
        if ($amount > self::LIMIT) {
            $amount = bcsub($amount, (string) self::LIMIT, 2);
        }

        return bcdiv(bcmul($amount, $fee), self::PERCANTAGE, 2);
    }

    private function getPreviousTransactions(
        string $clientId,
        int $end,
        string $opType = 'withdraw',
        $clientType = 'private'
    ): array {
        $previousTransactions = array_reverse(\array_slice($this->transactions, 0, $end));

        return array_filter($previousTransactions, function ($transaction) use ($clientId, $opType, $clientType) {
            return $transaction->getClient()->getId() === $clientId
                && $transaction->getType() === $opType
                && $transaction->getClient()->getType() === $clientType;
        });
    }

    private function getFeeRate(Transaction $transaction): string
    {
        if ($transaction->isDeposit()) {
            $fee = self::DEPOSIT_FEE;
        } elseif ($transaction->isWithdraw()) {
            $client = $transaction->getClient();
            if ($client->isPrivate()) {
                $fee = self::PRIVATE_WITHDRAW_FEE;
            } elseif ($client->isBusiness()) {
                $fee = self::BUSINESS_WITHDRAW_FEE;
            }
        }

        return $fee;
    }

    private function getAmountInEuro(Transaction $transaction): string
    {
        if ($transaction->isCurrencyJPY()) {
            $amount = ExchangeRate::eurJPY($transaction->getAmount());
        } elseif ($transaction->isCurrencyUSD()) {
            $amount = ExchangeRate::eurUSD($transaction->getAmount());
        } else {
            $amount = $transaction->getAmount();
        }

        return $amount;
    }
}
