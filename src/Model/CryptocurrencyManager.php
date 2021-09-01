<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class CryptocurrencyManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(string $id, string $symbol, string $name, float $price): int
    {
        $statement = $this->database->prepare('INSERT INTO cryptocurrencies (id, symbol, name, price) VALUES (:id, :symbol, :name, :price)');
        $binds = [
            ':id' => $id,
            ':symbol' => $symbol,
            ':name' => $name,
            ':price' => $price
        ];
        $statement->execute($binds);

        return (int) $this->database->lastInsertId();
    }

    public function getCryptocurrencyById(string $id): Cryptocurrency
    {
        $query = $this->database->prepare('SELECT * FROM cryptocurrencies WHERE id = :id');
        $query->setFetchMode(Database::FETCH_CLASS, Cryptocurrency::class);
        $query->execute([':id' => $id]);

        return $query->fetch(Database::FETCH_CLASS);
    }

    public function getAllCryptocurrencies(): array
    {
        $query = $this->database->query('SELECT * FROM cryptocurrencies');

        return $query->fetchAll(Database::FETCH_CLASS, Cryptocurrency::class);
    }

    public function updatePrice(string $id, float $price): void
    {
        // TODO

        $query = $this->database->prepare('UPDATE cryptocurrencies SET price=:price WHERE id=:id');
        $query->bindParam(':price', $price);
        $query->bindParam(':id', $id);
        $query->execute();
    }
}
