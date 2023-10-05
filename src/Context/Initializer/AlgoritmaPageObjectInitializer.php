<?php

namespace Algoritma\BehatExtension\Context\Initializer;

use Algoritma\BehatExtension\Element\AlgoritmaElementFactory;
use Algoritma\BehatExtension\Element\AlgoritmaPageFactory;
use Algoritma\BehatExtension\Element\AlgoritmaPageObjectAware;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

class AlgoritmaPageObjectInitializer implements ContextInitializer
{
    /**
     * @var AlgoritmaElementFactory
     */
    protected $elementFactory;

    /**
     * @var AlgoritmaPageFactory
     */
    protected $pageFactory;

    public function __construct(AlgoritmaElementFactory $elementFactory, AlgoritmaPageFactory $pageFactory)
    {
        $this->elementFactory = $elementFactory;
        $this->pageFactory = $pageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof AlgoritmaPageObjectAware) {
            $context->setElementFactory($this->elementFactory);
            $context->setPageFactory($this->pageFactory);
        }
    }
}
