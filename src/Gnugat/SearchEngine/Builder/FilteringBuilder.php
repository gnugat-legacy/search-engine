<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Criteria\Filtering;
use Gnugat\SearchEngine\ResourceDefinition;

class FilteringBuilder
{
    /**
     * @var array
     */
    private $prioritizedFilteringBuilderStrategies = array();

    /**
     * @var bool
     */
    private $isSorted = false;

    /**
     * @param FilteringBuilderStrategy $filteringBuilderStrategy
     * @param int                      $priority
     */
    public function add(FilteringBuilderStrategy $filteringBuilderStrategy, $priority = 0)
    {
        $this->prioritizedFilteringBuilderStrategies[$priority][] = $filteringBuilderStrategy;
        $this->isSorted = false;
    }

    /**
     * @param QueryBuilder       $queryBuilder
     * @param ResourceDefinition $resourceDefinition
     * @param Filtering          $filtering
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, Filtering $filtering)
    {
        if (!$this->isSorted) {
            $this->sortStrategies();
        }
        foreach ($filtering->getFields() as $field => $value) {
            foreach ($this->prioritizedFilteringBuilderStrategies as $priority => $filteringBuilderStrategies) {
                foreach ($filteringBuilderStrategies as $filteringBuilderStrategy) {
                    if ($filteringBuilderStrategy->supports($resourceDefinition, $field, $value)) {
                        $filteringBuilderStrategy->build($queryBuilder, $resourceDefinition, $field, $value);

                        break 2;
                    }
                }
            }
        }
    }

    /**
     * Sort registered strategies according to their priority.
     */
    private function sortStrategies()
    {
        krsort($this->prioritizedFilteringBuilderStrategies);
        $this->isSorted = true;
    }
}
