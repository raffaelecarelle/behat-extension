<?php

namespace Algoritma\BehatExtension\Element;

use Behat\Testwork\Suite\Suite;

interface SuiteAwareInterface
{
    public function setSuite(Suite $suite);
}
