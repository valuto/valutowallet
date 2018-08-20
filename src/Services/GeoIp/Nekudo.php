<?php

namespace Services\GeoIp;

class Nekudo
{
    public static function lookup($ip)
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://geoip.nekudo.com/api/' . $ip, [
            'connect_timeout' => 4,
        ]);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception('Nekudo returned status code ' . $res->getStatusCode());
        }

        $body = json_decode($res->getBody());

        if (self::jsonFailed()) {
            throw new \Exception('Nekudo response is not valid JSON.');
        }

        if (isset($body->type) && $body->type === 'error') {
            throw new \Exception('Nekudo returned the following error: ' . $body->msg);
        }

        return $body;
    }

    private static function jsonFailed()
    {
        return json_last_error() !== JSON_ERROR_NONE;
    }
}