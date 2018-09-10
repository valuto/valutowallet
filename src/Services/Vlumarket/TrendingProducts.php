<?php

namespace Services\Vlumarket;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;

class TrendingProducts
{
    protected static $emptyResponse = [];

    public static function get()
    {
        // @TODO add cache

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('GET', env('VLU_MARKET_URL') . '/api/v1/products/trending', [
                'connect_timeout' => 4,
            ]);
        } catch (ClientException $e) {
            return self::$emptyResponse;
        } catch (ServerException $e) {
            return self::$emptyResponse;
        } catch (ConnectException $e) {
            return self::$emptyResponse;
            
        }

        if ($res->getStatusCode() !== 200) {
            return self::$emptyResponse;
        }

        $body = json_decode((string)$res->getBody());

        if (self::jsonFailed()) {
            return self::$emptyResponse;
        }

        return $body;
    }

    private static function jsonFailed()
    {
        return json_last_error() !== JSON_ERROR_NONE;
    }
}