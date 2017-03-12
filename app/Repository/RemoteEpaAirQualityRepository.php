<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/12
 * Time: 下午2:51
 */

namespace App\Repository;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class RemoteEpaAirQualityRepository implements AirQualityRepositoryContract
{

    /**
     * The data source base url
     * @var
     */
    protected $baseUrl;

    /**
     * Guzzle HTTP client
     * @var ClientInterface
     */
    protected $client;

    protected $uri;

    public function __construct($baseUrl, ClientInterface $client)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
    }

    /**
     * Get all air quaility dataset from EPA server
     * @return mixed
     * @throws \Exception
     */
    public function getAll() {
        $request = $this->makeRequest();
        $response = $this->client->send($request, ['timeout' => 10]);

        if ($response->getStatusCode() === 200) {
            $body = $response->getBody();

            return json_decode($body);
        }

        // @TODO: Define an Exception
        throw new \Exception();
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * Make a HTTP request for Guzzle HTTP client
     * @return Request
     */
    protected function makeRequest() : Request {
        $method = 'GET';
        $url = $this->baseUrl . '/' . $this->uri;
        $headers = [
            'Accept' => 'application/json',
        ];

        return new Request($method, $url, $headers);
    }
}