<?php


namespace App\SigmaSms;

class Client
{
    const API_URL = 'https://online.sigmasms.ru/api/';

    /** @var Credentials */
    private $credentials;

    /** @var \GuzzleHttp\Client */
    private $httpClient;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;

        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => static::API_URL,
            'headers' => [
                'Authorization' => $credentials->token(),
            ]
        ]);
    }

    public function send($array)
    {
        return $this->httpClient->post('sendings', [
            'form_params' => $array,
        ]);
    }
}
