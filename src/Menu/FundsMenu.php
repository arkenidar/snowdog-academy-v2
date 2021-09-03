<?php

namespace Snowdog\Academy\Menu;

class FundsMenu extends AbstractMenu
{
    public function getHref(): string
    {
        return '/funds';
    }

    public function getLabel(): string
    {
        return 'Add Funds';
    }

    public function isVisible(): bool
    {
        return (bool)$_SESSION['login'];
    }
}
