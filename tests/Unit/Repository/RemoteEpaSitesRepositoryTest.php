<?php

use App\Repository\RemoteEpaSitesRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class RemoteEpaSitesRepositoryTest extends TestCase
{
    protected $fakeBaseUrl;

    protected $fakeBody;

    protected function setUp()
    {
        parent::setUp();

        $this->fakeBaseUrl = 'https://fake.epa.site';

        $jsonFilePath = realpath(__DIR__ . '/../../data/epa_sites.json');
        $this->fakeBody = file_get_contents($jsonFilePath);
    }

    /**
     *
     */
    public function testGetAll()
    {
        // Create mock response
        $mock = new MockHandler([
           new Response('200', [], $this->fakeBody),
        ]);

        /* The @var $container will have the request which is sent from @var $client */
        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $repository = new RemoteEpaSitesRepository($this->fakeBaseUrl, $client);
        $repository->setUri('/my-happy-endpoint');
        $jsonData = $repository->getAll();

        // Because $client may send multiple requests, it should check the count of $container
        $this->assertCount(1, $container);

        // Check the request data
        foreach ($container as $transaction) {
            $request = $transaction['request'];

            $this->assertEquals('GET', $request->getMethod());
            $this->assertTrue($request->hasHeader('Accept'));
            $this->assertEquals(['application/json'], $request->getHeader('Accept'));
            $this->assertEquals('https', $request->getUri()->getScheme());
            $this->assertEquals('fake.epa.site', $request->getUri()->getHost());
            $this->assertEquals('/my-happy-endpoint', $request->getUri()->getPath());
        }

        $this->assertCount(7, $jsonData->result->records);
    }

    public function testGetAllWithException()
    {
        // TODO: Need to test when the HTTP status code is not 200
    }

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }
}