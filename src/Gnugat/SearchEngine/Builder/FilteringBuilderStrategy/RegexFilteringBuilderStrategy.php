<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;
use Gnugat\SearchEngine\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;

class RegexFilteringBuilderStrategy implements FilteringBuilderStrategy
{
    /**
     * {@inheritDoc}
     */
    public function supports(ResourceDefinition $resourceDefinition, $field, $value)
    {
        return $resourceDefinition->hasField($field) && ResourceDefinition::TYPE_STRING === $resourceDefinition->getFieldType($field);
    }

    /**
     * {@inheritDoc}
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value)
    {
        $queryBuilder->andWhere("$field ~* :$field");
        $queryBuilder->setParameter(":$field", ".*$value.*", ResourceDefinition::TYPE_STRING);
    }
}
