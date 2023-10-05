<?php

namespace Algoritma\BehatExtension\Context;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Denotes the behat context class as aware of the kernel of the application being tested by behat.
 */
interface AppKernelAwareInterface
{
    public function setAppKernel(KernelInterface $appKernel): void;
}
