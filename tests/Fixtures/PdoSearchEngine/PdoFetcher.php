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
        $wrappedSql = 'SELECT array_to_json(array_agg(row_to_json(t))) FROM ('.$sql.') t';
        $result = $this->getPDOStatement($wrappedSql, $parameters)->fetch(PDO::FETCH_ASSOC);

        return (true === isset($result['array_to_json']) && null !== $result['array_to_json']) ? $result['array_to_json'] : '[]';
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFirst($sql, array $parameters)
    {
        return $this->getPDOStatement($sql, $parameters)->fetch(PDO::FETCH_ASSOC);
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
