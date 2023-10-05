<?php

namespace Algoritma\BehatExtension\Context\Initializer;

use Algoritma\BehatExtension\Context\AppKernelAwareInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Injects the kernel of the application being tested by behat.
 */
class AppKernelInitializer implements ContextInitializer
{
    private KernelInterface $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->appKernel->boot();
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context): void
    {
        if ($context instanceof AppKernelAwareInterface) {
            $context->setAppKernel($this->appKernel);
        }
    }
}
