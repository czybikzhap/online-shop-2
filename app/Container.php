<?php


class Container
{
    private array $services;

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function get(string $className): object
    {
        if(!isset($this->services[$className])) {
            return new $className;
        }

        $callback = $this->services[$className];

        return $callback($this);
    }

}