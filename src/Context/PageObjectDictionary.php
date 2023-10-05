<?php

namespace Algoritma\BehatExtension\Context;

use Algoritma\BehatExtension\Element\Element;
use Algoritma\BehatExtension\Element\AlgoritmaElementFactory;
use Algoritma\BehatExtension\Element\AlgoritmaPageFactory;
use Algoritma\BehatExtension\Element\Page;
use Behat\Mink\Element\NodeElement;

trait PageObjectDictionary
{
    /**
     * @var AlgoritmaElementFactory
     */
    protected $elementFactory;

    /**
     * @var AlgoritmaPageFactory
     */
    protected $pageFactory;

    /**
     * {@inheritdoc}
     */
    public function setElementFactory(AlgoritmaElementFactory $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    public function setPageFactory(AlgoritmaPageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasElement($name)
    {
        return $this->elementFactory->hasElement($name);
    }

    /**
     * @param string $name
     * @param NodeElement $context
     * @return Element
     */
    public function createElement($name, NodeElement $context = null)
    {
        return $this->elementFactory->createElement($name, $context);
    }

    /**
     * @param string $name
     * @param NodeElement|null $context
     * @return Element[]
     */
    public function findAllElements($name, NodeElement $context = null)
    {
        return $this->elementFactory->findAllElements($name, $context);
    }

    /**
     * @param string $name Element name
     * @param string $text Text that contains in element node
     * @param Element $context
     *
     * @return Element
     */
    public function findElementContains($name, $text, Element $context = null)
    {
        return $this->elementFactory->findElementContains($name, $text, $context);
    }

    /**
     * @return Page|Element
     */
    public function getPage($name = null)
    {
        if (null === $name) {
            return $this->elementFactory->getPage();
        }

        return $this->pageFactory->getPage($name);
    }

    /**
     * @param string $elementName
     * @param NodeElement $context
     *
     * @return bool
     */
    public function isElementVisible($elementName, NodeElement $context = null)
    {
        if ($this->hasElement($elementName)) {
            $element = $this->createElement($elementName, $context);
            if ($element->isValid() && $element->isVisible()) {
                return true;
            }
        }

        return false;
    }
}
