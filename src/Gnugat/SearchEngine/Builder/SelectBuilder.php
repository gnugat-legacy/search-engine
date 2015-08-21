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

use Gnugat\SearchEngine\Criteria;
use Gnugat\SearchEngine\ResourceDefinition;

interface SelectBuilder
{
    /**
     * @param QueryBuilder       $queryBuilder
     * @param ResourceDefinition $resourceDefinition
     * @param Criteria           $criteria
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, Criteria $criteria);
}
