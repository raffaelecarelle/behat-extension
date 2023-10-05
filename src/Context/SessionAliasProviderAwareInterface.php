<?php

namespace Algoritma\BehatExtension\Context;

interface SessionAliasProviderAwareInterface
{
    /**
     * @param SessionAliasProvider $provider
     * @return void
     */
    public function setSessionAliasProvider(SessionAliasProvider $provider);
}
