<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Kalimeraa\Commission\Service\Client;
use Kalimeraa\Commission\Service\Commission;
use Kalimeraa\Commission\Service\Transaction;

if (count($argv) <= 1) {
    throw new RuntimeException('Provide csv file');
}

if (!file_exists($argv[1])) {
    throw new RuntimeException('File does not exist');
}

require_once __DIR__.'/vendor/autoload.php';

$file = new SplFileObject(__DIR__.'/'.$argv[1]);
$file->setFlags(SplFileObject::READ_CSV);

$file->setCsvControl(';');

$commission = new Commission();
foreach ($file as $row) {
    [$opDate, $userId, $userType, $opType, $opAmount, $opCurrency] = $row;

    $client = (new Client())->setId($userId)->setType($userType);

    $transaction = new Transaction($opType, $opDate, $opCurrency, $opAmount, $client);

    $commission->addTransaction($transaction);
}

$commission->handle();
