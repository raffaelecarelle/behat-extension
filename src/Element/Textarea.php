<?php

namespace Algoritma\BehatExtension\Element;

class Textarea extends Element
{
    public function setValue($value)
    {
        $formattedValue = str_replace('\n', PHP_EOL, $value);
        parent::setValue($formattedValue);
    }
}
