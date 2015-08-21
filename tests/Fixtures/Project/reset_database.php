#!/usr/bin/env php
<?php

require __DIR__.'/../../../vendor/autoload.php';

use Gnugat\SearchEngine\Test\Fixtures\Project\ProjectBuild;

$config = ProjectBuild::config();
$pdo = new PDO(
    'pgsql:host='.$config['host'].' port='.$config['port'],
    $config['username'],
    $config['password']
);

try {
    $pdo->exec('DROP DATABASE '.$config['database']);
} catch (\Exception $e) {
}
$pdo->exec('CREATE DATABASE '.$config['database']);

$pdo = ProjectBuild::pdo();

$pdo->exec(<<<SQL
CREATE TABLE author (
    id INT NOT NULL PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
)
SQL
);
$pdo->exec("INSERT INTO author VALUES (1, 'Nate')");
$pdo->exec("INSERT INTO author VALUES (2, 'Nicolas')");
$pdo->exec("INSERT INTO author VALUES (3, 'Lorel')");

$pdo->exec(<<<SQL
CREATE TABLE blog (
    id INT NOT NULL PRIMARY KEY,
    title TEXT NOT NULL UNIQUE,
    author_id INT NOT NULL REFERENCES author(id) ON DELETE CASCADE
)
SQL
);
$pdo->exec("INSERT INTO blog VALUES (1, 'Big Title', 1)");
$pdo->exec("INSERT INTO blog VALUES (2, 'Big Header', 2)");
$pdo->exec("INSERT INTO blog VALUES (3, 'Ancient Title', 1)");
$pdo->exec("INSERT INTO blog VALUES (4, 'Ancient Header', 3)");
