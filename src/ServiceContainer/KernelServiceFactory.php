<?php

namespace Algoritma\BehatExtension\ServiceContainer;

use Symfony\Component\HttpKernel\KernelInterface;

class KernelServiceFactory
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        dump('asdsdsds');
        $this->kernel = $kernel;
        $this->kernel->boot();
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        return $this->kernel->getContainer()->get($id);
    }

    public function boot()
    {
        $this->kernel->boot();
    }

    public function shutdown()
    {
        $this->kernel->shutdown();
    }
}
