<?php

namespace Algoritma\BehatExtension\Driver;

use Behat\MinkExtension\ServiceContainer\Driver\Selenium2Factory;

class AlgoritmaSelenium2Factory extends Selenium2Factory
{
    /**
     * {@inheritdoc}
     */
    public function buildDriver(array $config)
    {
        $definition = parent::buildDriver($config);
        $definition->setClass('Algoritma\BehatExtension\Driver\AlgoritmaSelenium2Driver');

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return 'algoritmaSelenium2';
    }
}
