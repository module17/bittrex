<?php

namespace edsonmedina\bittrex;

use edsonmedina\bittrex\publicinfo\Market;

class PublicInfo
{
    const baseUrl = 'https://bittrex.com/api/v1.1/';

    /** @var \GuzzleHttp\Client */
    private $guzzle;

    static public function connect(): self
    {
        $guzzle = new \GuzzleHttp\Client([
            'base_uri' => self::baseUrl,
            'timeout' => 5
        ]);

        return new self($guzzle);
    }

    public function __construct(\GuzzleHttp\Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @param string $method
     * @param array $extraParams
     * @return mixed
     * @throws \RuntimeException
     */
    protected function call(string $method, array $extraParams = [])
    {
        $uri = self::baseUrl . $method;

        if (!empty($extraParams)) {
            $uri .= '&'.http_build_query($extraParams);
        }

        $options = [
            'headers' => [
                'User-Agent' => 'Bittrex client (github.com/edsonmedina/bittrex)',
            ]
        ];

        $httpResponse = $this->guzzle->request('GET', $uri, $options);
        $jsonString = $httpResponse->getBody()->getContents();

        $response = \GuzzleHttp\json_decode(
            $jsonString
        );

        if ($response->success !== true) {
            throw new \RuntimeException($response->message);
        }

        return $response->result;
    }

    /**
     * @return Market[]
     */
    public function getMarkets(): array
    {
        $response = $this->call('public/getmarkets');

        return array_map(
            function ($market) {
                return new Market(
                    $market->MarketCurrency,
                    $market->BaseCurrency,
                    $market->MarketCurrencyLong,
                    $market->BaseCurrencyLong,
                    $market->MinTradeSize,
                    $market->MarketName,
                    $market->IsActive,
                    $market->Created
                );
            },
            $response
        );
    }
}