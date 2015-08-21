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
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;

class RegexFilteringBuilderStrategy implements FilteringBuilderStrategy
{
    /**
     * {@inheritdoc}
     */
    public function supports(ResourceDefinition $resourceDefinition, $field, $value)
    {
        return $resourceDefinition->hasField($field) && ResourceDefinition::TYPE_STRING === $resourceDefinition->getFieldType($field);
    }

    /**
     * {@inheritdoc}
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value)
    {
        $queryBuilder->addWhere("$field ~* :$field");
        $queryBuilder->addParameter(":$field", ".*$value.*", ResourceDefinition::TYPE_STRING);
    }
}
