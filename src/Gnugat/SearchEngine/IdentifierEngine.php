<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine;

use Gnugat\SearchEngine\Builder\FilteringBuilder;
use Gnugat\SearchEngine\Builder\SelectBuilder;

class IdentifierEngine
{
    /**
     * @var FilteringBuilder
     */
    private $filteringBuilder;

    /**
     * @var QueryBuilderFactory
     */
    private $queryBuilderFactory;

    /**
     * @var array
     */
    private $resourceDefinitions = array();

    /**
     * @var array
     */
    private $selectBuilders = array();

    /**
     * @param FilteringBuilder    $filteringBuilder
     * @param QueryBuilderFactory $queryBuilderFactory
     */
    public function __construct(FilteringBuilder $filteringBuilder, QueryBuilderFactory $queryBuilderFactory)
    {
        $this->filteringBuilder = $filteringBuilder;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @param Criteria $criteria
     *
     * @return string
     *
     * @throws NoMatchException If nothing match
     */
    public function match(Criteria $criteria)
    {
        $resourceName = $criteria->getResourceName();
        if (!isset($this->resourceDefinitions[$resourceName]) || !isset($this->selectBuilders[$resourceName])) {
            throw new NoMatchException('No match found for resource "'.$resourceName.'"');
        }
        $resourceDefinition = $this->resourceDefinitions[$resourceName];
        $selectBuilder = $this->selectBuilders[$resourceName];
        $queryBuilder = $this->queryBuilderFactory->make();
        $selectBuilder->build($queryBuilder, $resourceDefinition, $criteria);
        $queryBuilder->from($resourceName);
        $this->filteringBuilder->build($queryBuilder, $resourceDefinition, $criteria->getFiltering());
        $result = json_decode($queryBuilder->execute());
        if (empty($result)) {
            throw new NoMatchException('No match found for resource "'.$resourceName.'"');
        }

        return json_encode($result[0]);
    }

    /**
     * @param string             $resourceName
     * @param ResourceDefinition $resourceDefinition
     * @param SelectBuilder      $selectBuilder
     */
    public function add($resourceName, ResourceDefinition $resourceDefinition, SelectBuilder $selectBuilder)
    {
        $this->resourceDefinitions[$resourceName] = $resourceDefinition;
        $this->selectBuilders[$resourceName] = $selectBuilder;
    }
}
