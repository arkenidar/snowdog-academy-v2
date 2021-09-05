<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class UserCryptocurrencyManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getCryptocurrenciesByUserId(int $userId): array
    {
        $query = $this->database->prepare('SELECT c.id, c.name, uc.amount FROM user_cryptocurrencies AS uc LEFT JOIN cryptocurrencies AS c ON uc.cryptocurrency_id = c.id WHERE uc.user_id = :user_id');
        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->execute();

        return $query->fetchAll(Database::FETCH_CLASS, UserCryptocurrency::class);
    }

    public function addCryptocurrencyToUser(int $userId, Cryptocurrency $cryptocurrency, int $amount): void
    {
        // TODO

        $this->variateUserCryptocurrency($userId,$cryptocurrency,$amount,"add");
    }

    public function subtractCryptocurrencyFromUser(int $userId, Cryptocurrency $cryptocurrency, int $amount): void
    {
        // TODO

        $this->variateUserCryptocurrency($userId,$cryptocurrency,$amount,"subtract");
    }

    // trick: code reuse
    public function variateUserCryptocurrency(int $userId, Cryptocurrency $cryptocurrency, int $amount, string $addOrSubtract) : void {
        // TODO

        if($amount<0) return;
        $userCryptocurrency=$this->getUserCryptocurrency($userId,$cryptocurrency->getId());
        if(!$userCryptocurrency){
            $previous_amount=0;
            $mode="insert";
        }else{
            $previous_amount=$userCryptocurrency->getAmount();
            $mode="update";
        }

        if($addOrSubtract=="add"){
            $amount=$previous_amount+$amount;
        }elseif($addOrSubtract=="subtract"){
            $amount=$previous_amount-$amount;
            if($amount<0) return;    
        }else return;
        
        if($mode=="update"){
            $where=' WHERE user_id=:user_id AND cryptocurrency_id=:cryptocurrency_id';
            if($amount==0){
                $sql='DELETE FROM user_cryptocurrencies'.$where;
            }else{
                $sql='UPDATE user_cryptocurrencies SET amount=:amount'.$where;
                $query->bindParam(':amount', $amount, Database::PARAM_INT);
            }
        }

        if($mode=="insert"){
            if($amount==0) return;
            $sql='INSERT INTO user_cryptocurrencies (amount,user_id,cryptocurrency_id) VALUES (:amount,:user_id,:cryptocurrency_id)';
            $query->bindParam(':amount', $amount, Database::PARAM_INT);
        }
        $query = $this->database->prepare($sql);
        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $cryptocurrencyId=$cryptocurrency->getId();
        $query->bindParam(':cryptocurrency_id', $cryptocurrencyId, Database::PARAM_STR);
        $query->execute();
    }

    public function getUserCryptocurrency(int $userId, string $cryptocurrencyId): ?UserCryptocurrency
    {
        $query = $this->database->prepare('SELECT * FROM user_cryptocurrencies WHERE user_id = :user_id AND cryptocurrency_id = :cryptocurrency_id');
        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->bindParam(':cryptocurrency_id', $cryptocurrencyId, Database::PARAM_STR);
        $query->execute();

        /** @var UserCryptocurrency $result */
        $result = $query->fetchObject(UserCryptocurrency::class);

        return $result ?: null;
    }

}
