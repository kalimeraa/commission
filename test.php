<?php

$file = new SplFileObject(__DIR__ . '/input.csv');
$file->setCsvControl(';');

foreach ($file as $row) {
    list($animal, $class, $legs) = $row;
}