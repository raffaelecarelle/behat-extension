<?php

namespace Algoritma\BehatExtension\Element;

/**
 * This field used email popup for "From" field
 */
class Select2Offscreen extends Element
{
    public function getValue()
    {
        $valueElement = $this->getParent()->find('css', 'span.select2-chosen');
        self::assertTrue($valueElement->isValid(), 'Value not found in element: '.$this->getOuterHtml());

        return $valueElement->getText();
    }
}
