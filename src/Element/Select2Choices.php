<?php

namespace Algoritma\BehatExtension\Element;

use Behat\Mink\Element\NodeElement;

/**
 * This field used email popup for "To" field
 */
class Select2Choices extends Element
{
    public function getValue()
    {
        $valueElements = $this->getParent()->getParent()->findAll('css', 'li.select2-search-choice');

        return array_map(function (NodeElement $element) {
            return $element->getText();
        }, $valueElements);
    }
}
