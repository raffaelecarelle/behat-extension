<?php

namespace Algoritma\BehatExtension\Element;

use App\Tests\Behat\Element\Select2Entity;

class Select2Share extends Select2Entity
{
    /**
     * @var string
     */
    protected $searchInputSelector = '.select2-search-field input';
}
