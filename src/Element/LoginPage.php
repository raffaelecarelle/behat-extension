<?php

namespace Algoritma\BehatExtension\Element;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginPage extends Page implements RouterAwareInterface
{
    use RouterAwareTrait;

    public function open(array $parameters = [])
    {
        if (!isset($this->router) || $this->router === null) {
            throw new NoSuchPropertyException('Router must be injected!');
        }
        
        $this->elementFactory->getPage()->getSession()->getDriver()->visit($this->router->generate($this->route, [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->elementFactory->getPage()->getSession()->getDriver()->waitForAjax();
    }
}
