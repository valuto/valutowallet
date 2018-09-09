<?php

namespace Services\Vlumarket;

class TrendingProducts
{
    public static function get()
    {
        // @TODO add cache
        
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', env('VLU_MARKET_URL') . '/api/v1/products/trending', [
            'connect_timeout' => 4,
        ]);

        if ($res->getStatusCode() !== 200) {
            return [];
        }

        return (string)$res->getBody();
    }

    private static function jsonFailed()
    {
        return json_last_error() !== JSON_ERROR_NONE;
    }
}