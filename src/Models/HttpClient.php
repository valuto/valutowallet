<?php

namespace Models;

class HttpClient {

    /**
     * POST request.
     * 
     * @param  string $url
     * @param  array  $fields
     * @return string the response.
     */
    public static function post($url, $fields)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

}