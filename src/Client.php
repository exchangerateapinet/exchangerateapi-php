<?php

declare(strict_types=1);

namespace ExchangeRateApi;

final class Client
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.exchangerateapi.net/v1')
    {
        if ($apiKey === '') {
            throw new \InvalidArgumentException('apiKey is required');
        }
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Latest rates for a base currency; optional symbols filter
     * @param string $base
     * @param array<int,string> $symbols
     * @return array<string,mixed>
     */
    public function latest(string $base, array $symbols = []): array
    {
        if ($base === '') {
            throw new \InvalidArgumentException('base is required');
        }
        $params = [
            'base' => $base,
            'apikey' => $this->apiKey,
        ];
        if (!empty($symbols)) {
            $params['symbols'] = implode(',', $symbols);
        }
        return $this->get('/latest', $params);
    }

    /**
     * Historical rates for a given date and base
     * @param string $date YYYY-MM-DD
     * @param string $base
     * @param array<int,string> $symbols
     * @return array<string,mixed>
     */
    public function historical(string $date, string $base, array $symbols = []): array
    {
        if ($date === '' || $base === '') {
            throw new \InvalidArgumentException('date and base are required');
        }
        $params = [
            'date' => $date,
            'base' => $base,
            'apikey' => $this->apiKey,
        ];
        if (!empty($symbols)) {
            $params['symbols'] = implode(',', $symbols);
        }
        return $this->get('/historical', $params);
    }

    /**
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    private function get(string $path, array $params): array
    {
        $url = $this->baseUrl . $path . '?' . http_build_query($params);
        $ctx = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
            ],
        ]);
        $resp = @file_get_contents($url, false, $ctx);
        if ($resp === false) {
            throw new \RuntimeException('request failed');
        }
        /** @var array<string,mixed>|null $json */
        $json = json_decode($resp, true);
        if (is_array($json) && isset($json['error'])) {
            $msg = is_array($json['error']) && isset($json['error']['message']) ? (string) $json['error']['message'] : 'API error';
            throw new \RuntimeException($msg);
        }
        return is_array($json) ? $json : [];
    }
}


