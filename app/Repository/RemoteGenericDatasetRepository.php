<?php

namespace App\Repository;


use App\Repository\Contracts\DatasetRepositoryContract;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class RemoteGenericDatasetRepository implements DatasetRepositoryContract
{
    protected $baseUrl;

    protected $path;

    protected $client;

    protected $options;

    public function __construct(string $baseUrl, ClientInterface $client)
    {
        $this->baseUrl = $baseUrl;
        $this->client = $client;
    }

    /**
     * Get all dataset
     * @return \stdClass
     * @throws \Exception
     */
    public function getAll(): \stdClass
    {
        if (!isset($this->options)) {
            $option = ['timeout' => 10];
        } else {
            $option = $this->options;
        }

        $request = $this->makeRequest();
        $response = $this->client->send($request, $option);

        $body = $response->getBody();

        Log::debug('RemoteGenericDatasetRepository::getAll()');
        Log::debug($request->getRequestTarget());
        Log::debug($body);

        return json_decode($body);
    }

    public function getBasedUrl(): string
    {
        return $this->baseUrl;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    protected function makeRequest(): Request
    {
        $method = 'GET';
        $url = $this->baseUrl . $this->path;
        $headers = [
            'Accept' => 'application/json',
        ];

        return new Request($method, $url, $headers);
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}