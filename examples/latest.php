<?php

require __DIR__ . '/../vendor/autoload.php';

$apiKey = getenv('EXCHANGERATEAPI_KEY') ?: 'YOUR_API_KEY';
$client = new ExchangeRateApi\Client($apiKey);

// Scenario A: base only
$latestUsd = $client->latest('USD');
echo 'Latest USD sample codes: ' . implode(',', array_slice(array_keys($latestUsd['rates'] ?? []), 0, 5)) . PHP_EOL;

// Scenario B: base with symbols filter
$subset = $client->latest('EUR', ['USD','GBP','JPY']);
echo 'Latest EUR subset: ' . json_encode($subset['rates'] ?? [], JSON_UNESCAPED_SLASHES) . PHP_EOL;


