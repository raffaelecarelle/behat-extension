<?php

namespace Algoritma\BehatExtension\Context\Initializer;

use Algoritma\BehatExtension\Context\SessionAliasProvider;
use Algoritma\BehatExtension\Context\SessionAliasProviderAwareInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

class SessionAliasAwareInitializer implements ContextInitializer
{
    /**
     * @var SessionAliasProvider
     */
    private $provider;

    public function __construct(SessionAliasProvider $provider)
    {
        $this->provider = $provider;
    }

    public function initializeContext(Context $context)
    {
        if ($context instanceof SessionAliasProviderAwareInterface) {
            $context->setSessionAliasProvider($this->provider);
        }
    }
}
