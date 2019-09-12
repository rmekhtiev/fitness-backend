<?php


namespace App\SigmaSms;


use Lcobucci\JWT\Token;
use Tymon\JWTAuth\JWT;

class Credentials
{
    private $username;

    private $password;

    private $jwtFilename;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct($username, $password, $jwtFilename = null)
    {
        $this->username = $username;
        $this->password = $password;

        $this->jwtFilename = $jwtFilename ?? config('services.sigmasms.jwt_filename');

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => Client::API_URL,
        ]);
    }

    /**
     * @return string
     */
    public function token()
    {
        if (!file_exists($this->jwtFilename)) {
            return $this->refreshToken();
        }

        $token = $this->readToken();

        if($this->isExpired($this->readToken())) {
            return $this->refreshToken();
        }

        return $token;
    }

    /**
     * @param string $token
     * @return boolean
     */
    protected function isExpired($token)
    {
        return $this->decodeToken($token)->payload->exp <= time();
    }

    /**
     * @param $token
     * @return object
     */
    protected function decodeToken($token)
    {
        list($header, $payload, $signature) = explode('.', $token);

        return (object)[
            'header' => json_decode(base64_decode($header)),
            'payload' => json_decode(base64_decode($payload)),
            'signature' => $signature
        ];
    }

    protected function readToken()
    {
        return file_get_contents($this->jwtFilename, true);
    }

    /**
     * @param bool $persist
     *
     * @return string JWT
     */
    protected function refreshToken($persist = true)
    {
        $response = $this->client->post('login', [
            'form_params' => [
                'username' => $this->username,
                'password' => $this->password,
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        $token = $response->token;

        if ($persist) {
            file_put_contents($this->jwtFilename, $token);
        }

        return $token;
    }
}
