# exchangerateapi-php

PHP wrapper for exchangerateapi.net with a tiny footprint and pragmatic defaults. It exposes two focused methods that map to the API’s primary endpoints.

- Website: [exchangerateapi.net](https://exchangerateapi.net)

### Why this client?
- No external HTTP dependencies (works anywhere `file_get_contents` is allowed)
- Clear, typed method signatures
- Familiar PHP arrays for response bodies

## Requirements
- PHP >= 8.0
- An API key from [exchangerateapi.net](https://exchangerateapi.net)

## Install
```bash
composer require exchangerateapinet/exchangerateapi-php
```

## Quick start
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use ExchangeRateApi\Client;

$client = new Client(getenv('EXCHANGERATEAPI_KEY'));

$latest = $client->latest('USD');
echo $latest['rates']['EUR'] ?? 'n/a';
```

## Configuration
```php
// Change base URL if needed (defaults to https://api.exchangerateapi.net/v1)
$client = new Client(
    apiKey: getenv('EXCHANGERATEAPI_KEY'),
    baseUrl: 'https://api.exchangerateapi.net/v1'
);
```

## API surface
Both methods return decoded associative arrays. If the API reports an error, a `RuntimeException` is thrown.

### latest(string $base, array $symbols = []): array
Fetch the newest conversion rates for a base currency.

Parameters:
- `base` (required): ISO currency code (e.g., `USD`, `EUR`).
- `symbols` (optional): limit the response to selected currency codes.

Example:
```php
$all     = $client->latest('USD');
$subset  = $client->latest('EUR', ['USD', 'GBP', 'JPY']);
```

### historical(string $date, string $base, array $symbols = []): array
Fetch rates for a given calendar date.

Parameters:
- `date` (required): `YYYY-MM-DD` (e.g., `2024-01-02`).
- `base` (required): ISO currency code.
- `symbols` (optional): restrict to selected codes.

Example:
```php
$onDate  = $client->historical('2024-01-02', 'USD');
$subset  = $client->historical('2024-01-02', 'EUR', ['USD', 'GBP', 'JPY']);
```

## End‑to‑end examples
Run with your key in the environment.
```bash
EXCHANGERATEAPI_KEY=your_api_key php examples/latest.php
EXCHANGERATEAPI_KEY=your_api_key php examples/historical.php
```

## Error handling
The client raises a `RuntimeException` when the API returns an error payload or when the HTTP request fails.
```php
try {
    $client->latest('XYZ'); // invalid base
} catch (\RuntimeException $e) {
    error_log('Request failed: ' . $e->getMessage());
}
```

## Free usage
exchangerateapi.net provides a generous free tier suitable for development, prototypes, and light production workloads. It includes:
- Access to the core endpoints (latest and historical)
- An API key you can obtain in minutes
- Practical rate limits to keep experimentation smooth

Exact allowances (daily/monthly limits, burst caps) can evolve over time. For the most accurate, up‑to‑date details, review the plan information at [exchangerateapi.net](https://exchangerateapi.net). If you outgrow the free tier, upgrading is seamless and requires no code changes in this client.

## Versioning
This library follows semantic versioning. New features appear in minor versions, breaking changes only in major releases.

## Contributing
Issues and pull requests are welcome in the GitHub repository. Please include clear steps to reproduce any problem.

## License
MIT
