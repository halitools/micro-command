<?php

namespace Halitools\MicroCommand\Request;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Halitools\MicroCommand\Exceptions\RemoteException;
use Halitools\MicroCommand\Exceptions\UnserializeResponseException;
use Halitools\MicroCommand\Response\ExceptionResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class RemoteMicroService extends MicroService
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var UriInterface
     */
    private $uri;

    protected $remoteException = RemoteException::class;

    /**
     * RemoteMicroService constructor.
     * @param ClientInterface $client
     * @param UriInterface $uri
     */
    public function __construct(ClientInterface $client, UriInterface $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    public function __call(string $method, array $arguments)
    {
        $command = new Command($this->getImplements(), $method, $arguments);

        $request = new Request('POST', $this->uri, ['Accept' => 'application/json'], base64_encode(serialize($command)));

        return $this->handleResponse($this->client->send($request));
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     */
    public function handleResponse(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        try {
            $response = unserialize(base64_decode($content));
        } catch (\ErrorException $exception) {
            throw new UnserializeResponseException($content);
        }
        if (is_a($response, ExceptionResponse::class)) {
            /** @var ExceptionResponse $response */
            /** @var RemoteException $remoteException */
            throw new $this->remoteException($response->getMessage(), $response->getCode());
        }
        return $response;
    }

    /**
     * @param $interface
     * @return RemoteMicroService
     */
    protected function createService($interface): RemoteMicroService
    {
        $service = new RemoteMicroService($this->client, $this->uri);
        $service->setImplements($interface);
        return $service;
    }

}