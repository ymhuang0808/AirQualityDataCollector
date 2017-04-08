<?php

namespace App\Repository;


use GuzzleHttp\ClientInterface;

class RemoteEpaSitesRepository implements SitesRepositoryContract
{
    use HttpRequestHelper;

    protected $client;

    public function __construct(string $baseUrl, ClientInterface $client)
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
}
