<?php

use Snowdog\Academy\Command\Migrate;
use Snowdog\Academy\Command\TestDbConnection;
use Snowdog\Academy\Command\UpdatePrices;
use Snowdog\Academy\Component\Menu;
use Snowdog\Academy\Component\Migrations;
use Snowdog\Academy\Controller\Account;
use Snowdog\Academy\Controller\Index;
use Snowdog\Academy\Controller\Login;
use Snowdog\Academy\Controller\Register;
use Snowdog\Academy\Controller\Cryptos;
use Snowdog\Academy\Controller\Funds;
use Snowdog\Academy\Menu\AccountMenu;
use Snowdog\Academy\Menu\LoginMenu;
use Snowdog\Academy\Menu\LogoutMenu;
use Snowdog\Academy\Menu\RegisterMenu;
use Snowdog\Academy\Menu\FundsMenu;
use Snowdog\Academy\Repository\CommandRepository;
use Snowdog\Academy\Repository\RouteRepository;

RouteRepository::registerRoute('GET', '/', Index::class, 'index');
RouteRepository::registerRoute('GET', '/login', Login::class, 'index');
RouteRepository::registerRoute('POST', '/login', Login::class, 'login');
RouteRepository::registerRoute('GET', '/logout', Login::class, 'logout');
RouteRepository::registerRoute('GET', '/register', Register::class, 'index');
RouteRepository::registerRoute('POST', '/register', Register::class, 'register');
RouteRepository::registerRoute('GET', '/cryptos', Cryptos::class, 'index');
RouteRepository::registerRoute('GET', '/cryptos/buy/{id}', Cryptos::class, 'buy');
RouteRepository::registerRoute('POST', '/cryptos/buy/{id}', Cryptos::class, 'buyPost');
RouteRepository::registerRoute('GET', '/cryptos/sell/{id}', Cryptos::class, 'sell');
RouteRepository::registerRoute('POST', '/cryptos/sell/{id}', Cryptos::class, 'sellPost');
RouteRepository::registerRoute('GET', '/account', Account::class, 'index');
RouteRepository::registerRoute('POST', '/account/addFunds', Account::class, 'addFunds');
RouteRepository::registerRoute('GET', '/funds', Funds::class, 'index');

Menu::register(LoginMenu::class, 100);
Menu::register(RegisterMenu::class, 200);
Menu::register(AccountMenu::class, 200);
Menu::register(LogoutMenu::class, 900);
Menu::register(FundsMenu::class, 300);

CommandRepository::registerCommand('test_db_connection', TestDbConnection::class, 'Tests database connection');
CommandRepository::registerCommand('migrate_db', Migrate::class, 'Performs database migration');
CommandRepository::registerCommand('update_prices', UpdatePrices::class, 'Updates cryptocurrencies prices in database');

Migrations::registerComponentMigration('Snowdog\\Academy', 3);
