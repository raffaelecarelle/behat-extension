<?php

namespace Algoritma\BehatExtension\Element;

abstract class Page
{
    /**
     * @var AlgoritmaElementFactory
     */
    protected $elementFactory;

    /**
     * @var string
     */
    protected $route;

    public function __construct(AlgoritmaElementFactory $elementFactory, $route)
    {
        $this->elementFactory = $elementFactory;
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Open page using parameters
     */
    abstract public function open(array $parameters = []);
}
