<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test\Fixtures\PdoSearchEngine;

use Gnugat\SearchEngine\Fetcher;
use Gnugat\SearchEngine\ResourceDefinition;
use PDO;

class PdoFetcher implements Fetcher
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var array
     */
    private $types;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->types = array(
            ResourceDefinition::TYPE_INTEGER => PDO::PARAM_INT,
            ResourceDefinition::TYPE_NULL => PDO::PARAM_NULL,
            ResourceDefinition::TYPE_STRING => PDO::PARAM_STR,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($sql, array $parameters)
    {
        return $this->getPDOStatement($sql, $parameters)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirst($sql, array $parameters)
    {
        $result = $this->getPDOStatement($sql, $parameters)->fetch(PDO::FETCH_ASSOC);

        return false !== $result ? $result : null;
    }

    /**
     * @param string $sql
     * @param array  $parameters
     *
     * @return \PDOStatement
     */
    private function getPDOStatement($sql, array $parameters)
    {
        $pdoStatement = $this->pdo->prepare($sql);
        foreach ($parameters as $parameter) {
            $pdoStatement->bindParam($parameter['name'], $parameter['value'], $this->types[$parameter['type']]);
        }
        $pdoStatement->execute();

        return $pdoStatement;
    }
}
