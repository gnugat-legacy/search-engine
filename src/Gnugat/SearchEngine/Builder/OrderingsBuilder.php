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

use Gnugat\SearchEngine\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;

class OrderingsBuilder
{
    /**
     * @param QueryBuilder       $queryBuilder
     * @param ResourceDefinition $resourceDefinition
     * @param array              $orderings
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, array $orderings)
    {
        foreach ($orderings as $ordering) {
            $field = $ordering->getField();
            if (!$resourceDefinition->hasField($field)) {
                continue;
            }
            $queryBuilder->addOrderBy($field, $ordering->getDirection());
        }
    }
}
