[POLSKA WERSJA](/README.md)

# Snowdog Academy - recruitment task

In this recruitment task you will have to add a few new features to a cryptocurrency exchange application. In the current revision, users are able to display a list of available crypto assets.

## Running the application

The application can be run directly on the host machine or using a Docker environment.

### Docker

`.env` file must be created in the main folder (you can base on `.env.example`).

From the main folder run the following command:
```
docker-compose up -d
``` 
Containers with the application and database will be created. After that install required Composer libraries:
```
docker exec -it snowdog-academy_php_1 sh -c 'composer install'
```
The application will be accessible via http://127.0.0.1:8000.

To remove the containers run:
```
docker-compose down
```

### Host
Requirements:

* [Composer](https://getcomposer.org/)
* [PHP 7.4](https://www.php.net/manual/en/install.php)
* [MySQL 5.7](https://dev.mysql.com/doc/refman/5.7/en/installing.html)

In the main folder run a command to install required Composer packages:
```
composer install
```

After that start PHP built-in server:
```
php -S 0.0.0.0:8000 -t web/
```
The application will be accessible via http://127.0.0.1:8000.

## Creating database structure

Database configuration is placed in the `config.ini` file - it can be created based on `config.ini.example` and filled with proper values to your environment.

When the application is up and running for the first time, you have to run a script that will create all necessary tables in the database and will fill them with some data.

For Docker-based environment:
```
docker exec -it snowdog-academy_php_1 sh -c 'php console.php migrate_db'
```

For environment running on the host machine (run from the main folder):
```
php console.php migrate_db
```

## Tasks

### Task 0
Create a fork of this repository and commit all changes there. Each task should be a separate, properly described commit.

### Task 1
Add possibility to buy selected cryptocurrency.

### Task 2
Add possibility to sell selected cryptocurrency.

### Task 3
Add fetching current prices of cryptocurrencies from API [CoinCap](https://docs.coincap.io/), [CoinGecko](https://www.coingecko.com/api/documentations/v3) or any other chosen by you using the `php console.php update_prices` command. In these two APIs, `id` field in `cryptocurrencies` table matches the asset ID in these two systems.

### Task 4

Add a new menu item named **Add Funds**. After clicking on it, the app should display a simple form that allows you to add funds to your account (one input field for amount and one submit button).

## Notes
If you think the code needs refactoring or something can be done better or be optimised - do it. For sure, it will affect the score.
