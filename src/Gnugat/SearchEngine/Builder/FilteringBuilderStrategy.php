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

use Gnugat\SearchEngine\ResourceDefinition;

interface FilteringBuilderStrategy
{
    /**
     * @param ResourceDefinition $resourceDefinition
     * @param string             $field
     * @param mixed              $value
     *
     * @return bool
     */
    public function supports(ResourceDefinition $resourceDefinition, $field, $value);

    /**
     * @param QueryBuilder       $queryBuilder
     * @param ResourceDefinition $resourceDefinition
     * @param string             $field
     * @param mixed              $value
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value);
}
