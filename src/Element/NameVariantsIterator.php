<?php

namespace Algoritma\BehatExtension\Element;

use Algoritma\BehatExtension\Element\Transformers\NamePartsTransformerInterface;

/**
 * Iterates over Behat element name variants
 */
class NameVariantsIterator implements \IteratorAggregate
{
    /** @var array|string[] */
    private $glues = [''];

    /** @var string */
    private $name;

    /** @var NamePartsTransformerInterface[] */
    private $transformers = [];

    /**
     * @param string $name
     * @param null|string[] $customGlues
     */
    public function __construct($name, array $customGlues = null)
    {
        $this->name = $name;
        if ($customGlues) {
            $this->glues = $customGlues;
        }
    }

    public function addPartsTransformer(NamePartsTransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @return \Generator
     */
    private function splitNameParts()
    {
        //split by delimiters
        if (preg_match_all('/[_\- ]/', $this->name, $matches)) {
            $symbols = implode('', array_map('preg_quote', array_unique($matches[0])));
            yield preg_split(sprintf('/[%s]/', $symbols), $this->name);
        }
        //split by upper case letter
        yield preg_split('/(?=[A-Z])/', $this->name);
    }

    /**
     * @return \Generator
     */
    private function sets()
    {
        /**
         * @return \Generator|array[]
         */
        $lowerSets = function () {
            foreach ($this->splitNameParts() as $parts) {
                foreach ($this->transformers as $transformer) {
                    if ($transformer->isApplicable($parts)) {
                        yield $transformer->transform($parts);
                    }
                }
                yield $parts;
            }
        };
        foreach ($lowerSets() as $set) {
            yield $set; //original case
            $lowerSet = array_map('strtolower', $set);
            yield array_map('ucfirst', $lowerSet); //camel case
            yield $lowerSet; //lower case
        }
    }

    /**
     * @return \Generator
     */
    public function getIterator(): \Traversable
    {
        yield $this->name;

        foreach ($this->sets() as $set) {
            foreach ($this->glues as $glue) {
                yield implode($glue, $set);
            }
        }
    }
}
