<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class RegexFilteringBuilderStrategySpec extends ObjectBehavior
{
    function it_is_a_filtering_builder_strategy()
    {
        $this->shouldImplement('Gnugat\SearchEngine\Builder\FilteringBuilderStrategy');
    }

    function it_supports_existing_string_fields(ResourceDefinition $resourceDefinition)
    {
        $resourceDefinition->hasField('field')->willReturn(true);
        $resourceDefinition->getFieldType('field')->willReturn(ResourceDefinition::TYPE_STRING);

        $this->supports($resourceDefinition, 'field', 'value')->shouldBe(true);
    }

    function it_builds_a_regex_statement(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition)
    {
        $queryBuilder->addWhere('field ~* :field')->shouldBeCalled();
        $queryBuilder->addParameter(':field', '.*value.*', ResourceDefinition::TYPE_STRING)->shouldBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'field', 'value');
    }
}
