<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class UserManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByLogin(string $login): ?User
    {
        $query = $this->database->prepare('SELECT * FROM users WHERE login = :login');
        $query->setFetchMode(Database::FETCH_CLASS, User::class);
        $query->bindParam(':login', $login, Database::PARAM_STR);
        $query->execute();

        $result = $query->fetch(Database::FETCH_CLASS);
        return $result ?: null;
    }

    public function getById(int $id): User
    {
        $query = $this->database->prepare('SELECT * FROM users WHERE id = :id');
        $query->setFetchMode(Database::FETCH_CLASS, User::class);
        $query->bindParam(':id', $id, Database::PARAM_INT);
        $query->execute();

        return $query->fetch(Database::FETCH_CLASS);
    }

    public function create(string $login, string $password, float $funds): int
    {
        $hash = $this->hashPassword($password);
        $statement = $this->database->prepare('INSERT INTO users (login, password, funds) VALUES (:login, :password, :funds)');
        $statement->bindParam(':login', $login, Database::PARAM_STR);
        $statement->bindParam(':password', $hash, Database::PARAM_STR);
        $statement->bindParam(':funds', $funds, Database::PARAM_STR);
        $statement->execute();

        return (int) $this->database->lastInsertId();
    }

    public function verifyPassword(User $user, $password): bool
    {
        return $this->hashPassword($password) === $user->getPasswordHash();
    }

    private function hashPassword(string $password): string
    {
        return hash('sha512', $password);
    }

    public function setFunds(float $funds, User $user) : void
    {

        $id=$user->getId();

        $query = $this->database->prepare('UPDATE users SET funds=:funds WHERE id=:id');
        $query->bindParam(':funds',$funds);
        $query->bindParam(':id',$id);
        $query->execute();

        $user->setFunds($funds);
    }
}
