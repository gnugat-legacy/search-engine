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
use Gnugat\SearchEngine\Builder\OrderingsBuilder;
use Gnugat\SearchEngine\Builder\PaginatingBuilder;
use Gnugat\SearchEngine\Builder\SelectBuilder;

class SearchEngine
{
    /**
     * @var FilteringBuilder
     */
    private $filteringBuilder;

    /**
     * @var OrderingsBuilder
     */
    private $orderingsBuilder;

    /**
     * @var PaginatingBuilder
     */
    private $paginatingBuilder;

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
     * @param OrderingsBuilder    $orderingsBuilder
     * @param PaginatingBuilder   $paginatingBuilder
     * @param QueryBuilderFactory $queryBuilderFactory
     */
    public function __construct(
        FilteringBuilder $filteringBuilder,
        OrderingsBuilder $orderingsBuilder,
        PaginatingBuilder $paginatingBuilder,
        QueryBuilderFactory $queryBuilderFactory
    ) {
        $this->filteringBuilder = $filteringBuilder;
        $this->orderingsBuilder = $orderingsBuilder;
        $this->paginatingBuilder = $paginatingBuilder;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @param Criteria $criteria
     *
     * @return string
     */
    public function match(Criteria $criteria)
    {
        $paginating = $criteria->getPaginating();
        $currentPage = $paginating->getCurrentPage();
        $perPage = $paginating->getItemsPerPage();

        $resourceName = $criteria->getResourceName();
        if (!isset($this->resourceDefinitions[$resourceName]) || !isset($this->selectBuilders[$resourceName])) {
            return '{"items":[],"page":{"current_page":'.$currentPage.',"per_page":'.$perPage.',"total_elements":0,"total_pages":0}}';
        }
        $resourceDefinition = $this->resourceDefinitions[$resourceName];
        $selectBuilder = $this->selectBuilders[$resourceName];
        $queryBuilder = $this->queryBuilderFactory->make();
        $queryBuilder->from($resourceName);
        $this->filteringBuilder->build($queryBuilder, $resourceDefinition, $criteria->getFiltering());

        $queryBuilder->select('COUNT(id) AS total');
        $countResults = $queryBuilder->fetchFirst();
        $totalElements = (int) $countResults['total'];
        $totalPages = (int) ceil($totalElements / $perPage);

        $selectBuilder->build($queryBuilder, $resourceDefinition, $criteria);
        $this->paginatingBuilder->build($queryBuilder, $paginating);
        $this->orderingsBuilder->build($queryBuilder, $resourceDefinition, $criteria->getOrderings());
        $items = $queryBuilder->fetchAll();

        return '{"items":'.$items.',"page":{"current_page":'.$currentPage.',"per_page":'.$perPage.',"total_elements":'.$totalElements.',"total_pages":'.$totalPages.'}}';
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
