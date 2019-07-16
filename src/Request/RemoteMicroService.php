<?php

namespace Halitools\MicroCommand\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Halitools\MicroCommand\Exceptions\RemoteException;
use Halitools\MicroCommand\Exceptions\UnserializeResponseException;
use Halitools\MicroCommand\Response\ExceptionResponse;
use Halitools\MicroCommand\Response\ExceptionResponseInterface;
use Psr\Http\Message\ResponseInterface;

class RemoteMicroService extends MicroService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Uri
     */
    private $uri;

    protected $remoteException = RemoteException::class;

    /**
     * RemoteMicroService constructor.
     * @param Client $client
     * @param Uri $uri
     */
    public function __construct(Client $client, Uri $uri)
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
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Uri $uri
     */
    public function setUri(Uri $uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws RemoteException
     * @throws UnserializeResponseException
     */
    public function handleResponse(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        try {
            $response = unserialize(base64_decode($content));
        } catch (\ErrorException $exception) {
            throw new UnserializeResponseException($content);
        }
        if ($response instanceof ExceptionResponseInterface) {
            /** @var ExceptionResponseInterface $response */
            /** @var RemoteException $remoteException */
            $response->throw();
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