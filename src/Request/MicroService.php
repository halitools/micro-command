<?php


namespace Halitools\MicroCommand\Request;

use Halitools\MicroCommand\Exceptions\ImplementationNotFoundException;

abstract class MicroService
{

    protected $name;

    /**
     * @var array
     */
    protected $implementations = [];

    /**
     * @var string
     */
    protected $implements = '';

    /**
     * @var string|null
     */
    protected $namespace = null;


    /**
     * @param $key
     * @return mixed
     * @throws ImplementationNotFoundException
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->implementations)) {
            return $this->createService($this->implementations[$key]);
        }

        return $this->magicService($key);
    }

    /**
     * @return array
     */
    public function getImplementations(): array
    {
        return $this->implementations;
    }

    /**
     * @param array $implementations
     */
    public function setImplementations(array $implementations): void
    {
        $this->implementations = $implementations;
    }

    /**
     * @return string
     */
    public function getImplements(): string
    {
        return $this->implements;
    }

    /**
     * @param string $implements
     */
    public function setImplements(string $implements): void
    {
        $this->implements = $implements;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $namespace
     */
    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    abstract protected function createService($key);

    /**
     * @param $key
     * @return mixed
     * @throws ImplementationNotFoundException
     */
    public function magicService($key)
    {
        if (empty($this->implements) && empty($this->namespace)) {
            throw new ImplementationNotFoundException('No implementation found for ' . $key);
        }
        $namespace = $this->namespace ?? preg_replace('/\\\\[A-Za-z0-9]{0,}$/', '', $this->implements);
        $namespace .= '\\';
        $interface = $namespace . ucfirst($key) . 'Interface';
        if (interface_exists($interface)) {
            return $this->createService($interface);
        }
        throw new ImplementationNotFoundException('No implementation found for ' . $key);
    }

    public function getName(): string
    {
        return $this->name ?? (defined('static::NAME') ? $this::NAME : '');
    }

}