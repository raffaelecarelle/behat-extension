<?php

namespace Algoritma\BehatExtension\Element;

use Symfony\Component\Routing\RouterInterface;

interface RouterAwareInterface
{
    public function setRouter(RouterInterface $router);
}