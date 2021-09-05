<?php

namespace Snowdog\Academy\Menu;

class CryptosMenu extends AbstractMenu
{
    public function getHref(): string
    {
        return '/cryptos';
    }

    public function getLabel(): string
    {
        return 'Cryptos';
    }

    public function isVisible(): bool
    {
        return (bool)$_SESSION['login'];
    }
}
