<?php

namespace App\Repository;


use GuzzleHttp\Psr7\Request;

/**
 * This traits helps repository connect with HTTP resource
 *
 * @package App\Repository
 */
trait HttpRequestHelper
{
    protected $baseUrl;

    protected $uri;

    /**
     * Set HTTP(s) Uri
     *
     * @param string $uri
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Create a generic Request
     * @return Request
     */
    protected function makeRequest(): Request
    {
        $method = 'GET';
        $url = $this->baseUrl . $this->uri;
        $headers = [
            'Accept' => 'application/json',
        ];

        return new Request($method, $url, $headers);
    }
}