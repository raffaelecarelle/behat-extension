<?php

namespace Algoritma\BehatExtension\Element;

class TypeableInput extends Element
{
    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        parent::setValue(new InputValue(InputMethod::TYPE, $value));
    }
}
