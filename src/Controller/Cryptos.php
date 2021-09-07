<?php

namespace Snowdog\Academy\Controller;

use Snowdog\Academy\Model\Cryptocurrency;
use Snowdog\Academy\Model\CryptocurrencyManager;
use Snowdog\Academy\Model\UserCryptocurrencyManager;
use Snowdog\Academy\Model\UserManager;

class Cryptos
{
    private CryptocurrencyManager $cryptocurrencyManager;
    private UserCryptocurrencyManager $userCryptocurrencyManager;
    private UserManager $userManager;
    private Cryptocurrency $cryptocurrency;

    public function __construct(
        CryptocurrencyManager $cryptocurrencyManager,
        UserCryptocurrencyManager $userCryptocurrencyManager,
        UserManager $userManager
    ) {
        $this->cryptocurrencyManager = $cryptocurrencyManager;
        $this->userCryptocurrencyManager = $userCryptocurrencyManager;
        $this->userManager = $userManager;
    }

    public function index(): void
    {
        require __DIR__ . '/../view/cryptos/index.phtml';
    }

    public function buy(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            $_SESSION['flash'] = 'User not logged in!';
            header('Location: /login');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            $_SESSION['flash'] = 'Cryptocurrency not found by ID!';
            header('Location: /cryptos');
            return;
        }

        $this->cryptocurrency = $cryptocurrency;
        require __DIR__ . '/../view/cryptos/buy.phtml';
    }

    public function buyPost(string $id): void
    {
        // TODO
        // verify if user is logged in
        // use $this->userCryptocurrencyManager->addCryptocurrencyToUser() method

        // verify if user is logged in
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            $_SESSION['flash'] = 'User not logged in!';
            header('Location: /login');
            return;
        }

        // use $this->userCryptocurrencyManager->addCryptocurrencyToUser() method
        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            $_SESSION['flash'] = 'Cryptocurrency not found by ID!';
            header('Location: /cryptos');
            return;
        }

        $userId=$user->getId();
        $amount=filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);

        if($amount>=0){
            $difference=$cryptocurrency->getPrice()*$amount;
            
            $funds=$user->getFunds();
            $funds=$funds-$difference;
            if($funds>=0){
                $this->userManager->setFunds($funds,$user);

                $this->userCryptocurrencyManager->addCryptocurrencyToUser($userId,$cryptocurrency,$amount);
            }         
        }

        header('Location: /cryptos');
    }

    public function sell(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            header('Location: /account');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            header('Location: /account');
            return;
        }

        $this->cryptocurrency = $cryptocurrency;
        require __DIR__ . '/../view/cryptos/sell.phtml';
    }

    public function sellPost(string $id): void
    {
        // TODO
        // verify if user is logged in
        // use $this->userCryptocurrencyManager->subtractCryptocurrencyFromUser() method

        // verify if user is logged in
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            $_SESSION['flash'] = 'User not logged in!';
            header('Location: /login');
            return;
        }

        // use $this->userCryptocurrencyManager->subtractCryptocurrencyFromUser() method
        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            $_SESSION['flash'] = 'Cryptocurrency not found by ID!';
            header('Location: /cryptos');
            return;
        }

        $userId=$user->getId();
        $amount=filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);
        
        $available=$this->userCryptocurrencyManager->getUserCryptocurrency($userId,$id)->getAmount();

        if($amount>=0 && $amount<=$available){
            $difference=$cryptocurrency->getPrice()*$amount;
            
            $funds=$user->getFunds();
            $funds=$funds+$difference;
            $this->userManager->setFunds($funds,$user);

            $this->userCryptocurrencyManager->subtractCryptocurrencyFromUser($userId,$cryptocurrency,$amount);
        }

        header('Location: /account');
    }

    public function getCryptocurrencies(): array
    {
        return $this->cryptocurrencyManager->getAllCryptocurrencies();
    }
}