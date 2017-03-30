<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/12
 * Time: 下午5:11
 */

namespace App\Repository;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;

class RemoteEpaSitesRepository implements SitesRepositoryContract
{
    protected $baseUrl;

    protected $client;

    protected $uri;

    public function __construct($baseUrl, ClientInterface $client)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
    }

    /**
     * Get all sites
     * @return mixed
     * @throws \Exception
     */
    public function getAll(): \stdClass
    {
        $request = $this->makeRequest();
        $response = $this->client->send($request, ['timeout' => 10]);

        if ($response->getStatusCode() === 200) {
            $body = $response->getBody();

            return json_decode($body);
        }

        // @TODO: Define an Exception
        throw new \Exception();
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    protected function makeRequest(): Request
    {
        $method = 'GET';
        $url = $this->baseUrl . '/' . $this->uri;
        $headers = [
            'Accept' => 'application/json',
        ];

        return new Request($method, $url, $headers);
    }
}
