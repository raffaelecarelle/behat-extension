<?php

namespace Algoritma\BehatExtension\Element;

use Symfony\Component\Routing\RouterInterface;

trait RouterAwareTrait
{
    private RouterInterface $router;

    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}