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

        $amount_decrement=$amount;
        if($amount_decrement<0) return;
        $userCryptocurrency=$this->getUserCryptocurrency($userId,$cryptocurrency->getId());
        if(!$userCryptocurrency){
            $previous_amount=0;
            $mode="insert";
        }else{
            $previous_amount=$userCryptocurrency->getAmount();
            $mode="update";
        }
        $amount=$previous_amount+$amount_decrement;

        if($mode=="update")
        /*
        UPDATE table_name
        SET column1 = value1, column2 = value2, ...
        WHERE condition;
        */
        $query = $this->database->prepare('UPDATE user_cryptocurrencies SET amount=:amount WHERE user_id = :user_id AND cryptocurrency_id = :cryptocurrency_id');

        if($mode=="insert")
        /*
        INSERT INTO table_name (column1, column2, column3, ...)
        VALUES (value1, value2, value3, ...);
        */
        $query = $this->database->prepare('INSERT INTO user_cryptocurrencies (amount,user_id,cryptocurrency_id) VALUES (:amount,:user_id,:cryptocurrency_id);');

        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->bindParam(':cryptocurrency_id', $cryptocurrency->getId(), Database::PARAM_STR);
        $query->bindParam(':amount', $amount, Database::PARAM_INT);
        $query->execute();
    }

    public function subtractCryptocurrencyFromUser(int $userId, Cryptocurrency $cryptocurrency, int $amount): void
    {
        // TODO

        $amount_decrement=$amount;
        if($amount_decrement<0) return;
        $userCryptocurrency=$this->getUserCryptocurrency($userId,$cryptocurrency->getId());
        if(!$userCryptocurrency){
            $previous_amount=0;
            $mode="insert";
        }else{
            $previous_amount=$userCryptocurrency->getAmount();
            $mode="update";
        }
        $amount=$previous_amount-$amount_decrement;
        if($amount<0) return;

        if($mode=="update")
        /*
        UPDATE table_name
        SET column1 = value1, column2 = value2, ...
        WHERE condition;
        */
        $query = $this->database->prepare('UPDATE user_cryptocurrencies SET amount=:amount WHERE user_id = :user_id AND cryptocurrency_id = :cryptocurrency_id');

        if($mode=="insert")
        /*
        INSERT INTO table_name (column1, column2, column3, ...)
        VALUES (value1, value2, value3, ...);
        */
        $query = $this->database->prepare('INSERT INTO user_cryptocurrencies (amount,user_id,cryptocurrency_id) VALUES (:amount,:user_id,:cryptocurrency_id);');

        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->bindParam(':cryptocurrency_id', $cryptocurrency->getId(), Database::PARAM_STR);
        $query->bindParam(':amount', $amount, Database::PARAM_INT);
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
