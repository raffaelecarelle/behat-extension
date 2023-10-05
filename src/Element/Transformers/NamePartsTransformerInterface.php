<?php

namespace Algoritma\BehatExtension\Element\Transformers;

interface NamePartsTransformerInterface
{
    /**
     * @param array $nameParts
     * @return boolean
     */
    public function isApplicable(array $nameParts);

    /**
     * @param array $nameParts
     * @return array $parts
     */
    public function transform(array $nameParts);
}
