<?php

namespace Halitools\MicroCommand\Request;

class Command
{

    /** @var string  */
    private $interface;

    /** @var string  */
    private $method;

    /** @var array  */
    private $arguments;

    /**
     * Command constructor.
     * @param $interface
     * @param $method
     * @param $arguments
     */
    public function __construct(string $interface, string $method, array $arguments)
    {
        $this->interface = $interface;
        $this->method = $method;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}