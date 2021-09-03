<?php

namespace Snowdog\Academy\Controller;

class Funds
{
    public function index(): void
    {
        require __DIR__ . '/../view/funds/index.phtml';
    }
}