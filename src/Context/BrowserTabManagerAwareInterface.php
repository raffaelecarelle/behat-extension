<?php

namespace Algoritma\BehatExtension\Context;

/**
 * Interface for the context.
 */
interface BrowserTabManagerAwareInterface
{
    /**
     * @param BrowserTabManager $browserTabManager
     * @return void
     */
    public function setBrowserTabManager(BrowserTabManager $browserTabManager);
}
