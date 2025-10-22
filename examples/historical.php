<?php

require __DIR__ . '/../vendor/autoload.php';

$apiKey = getenv('EXCHANGERATEAPI_KEY') ?: 'YOUR_API_KEY';
$client = new ExchangeRateApi\Client($apiKey);

// Scenario A: historical for a date with base only
$histUsd = $client->historical('2024-01-02', 'USD');
echo 'Historical USD sample codes: ' . implode(',', array_slice(array_keys($histUsd['rates'] ?? []), 0, 5)) . PHP_EOL;

// Scenario B: historical with symbols filter
$subset = $client->historical('2024-01-02', 'EUR', ['USD','GBP','JPY']);
echo 'Historical EUR subset: ' . json_encode($subset['rates'] ?? [], JSON_UNESCAPED_SLASHES) . PHP_EOL;


