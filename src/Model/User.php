<?php

namespace Snowdog\Academy\Model;

class User
{
    private int $id;
    private string $login;
    private string $password;
    private float $funds;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    public function getFunds(): float
    {
        return $this->funds;
    }
    
    public function setFunds(float $funds): void
    {
        $this->funds=$funds;
    }
}
