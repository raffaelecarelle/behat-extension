<?php

namespace Algoritma\BehatExtension\Context\Initializer;

use Algoritma\BehatExtension\Context\BrowserTabManager;
use Algoritma\BehatExtension\Context\BrowserTabManagerAwareInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

/**
 * Sets manager to the behat context.
 */
class BrowserTabManagerAwareInitializer implements ContextInitializer
{
    /**
     * @var BrowserTabManager
     */
    private $manager;

    public function __construct(BrowserTabManager $manager)
    {
        $this->manager = $manager;
    }

    public function initializeContext(Context $context)
    {
        if ($context instanceof BrowserTabManagerAwareInterface) {
            $context->setBrowserTabManager($this->manager);
        }
    }
}
