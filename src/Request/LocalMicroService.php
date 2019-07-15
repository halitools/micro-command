<?php

namespace Halitools\MicroCommand\Request;

use Psr\Container\ContainerInterface;

class LocalMicroService extends MicroService
{

    /** @var ContainerInterface */
    private $container;

    /**
     * LocalMicroService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    protected function createService($key)
    {
        return $this->container->get($key);
    }
}