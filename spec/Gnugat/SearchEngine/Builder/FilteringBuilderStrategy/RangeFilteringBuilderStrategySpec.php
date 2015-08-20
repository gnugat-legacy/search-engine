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

use Gnugat\SearchEngine\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Service\TypeSanitizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RangeFilteringBuilderStrategySpec extends ObjectBehavior
{
    function let(TypeSanitizer $typeSanitizer)
    {
        $this->beConstructedWith($typeSanitizer);
    }

    function it_is_a_filtering_builder_strategy()
    {
        $this->shouldImplement('Gnugat\SearchEngine\Builder\FilteringBuilderStrategy');
    }

    function it_supports_pluralized_field_names(ResourceDefinition $resourceDefinition)
    {
        $resourceDefinition->hasField('field')->willReturn(true);
        $resourceDefinition->hasField('fields')->willReturn(false);

        $this->supports($resourceDefinition, 'fields', '')->shouldBe(true);
    }

    function it_avoids_building_in_statements_for_empty_values(
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $resourceDefinition->getFieldType('field')->shouldNotBeCalled();
        $queryBuilder->andWhere('field IN (:fields)')->shouldNotBeCalled();
        $queryBuilder->setParameter(':fields', Argument::any(), ResourceDefinition::TYPE_ARRAY)->shouldNotBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'fields', '');
    }

    function it_avoids_building_in_statements_for_invalid_empty_values(
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $resourceDefinition->getFieldType('field')->shouldNotBeCalled();
        $queryBuilder->andWhere('field IN (:fields)')->shouldNotBeCalled();
        $queryBuilder->setParameter(':fields', Argument::any(), ResourceDefinition::TYPE_ARRAY)->shouldNotBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'fields', ',');
    }

    function it_builds_in_statements_with_single_values(
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition,
        TypeSanitizer $typeSanitizer
    ) {
        $resourceDefinition->getFieldType('field')->willReturn(ResourceDefinition::TYPE_INTEGER);
        $typeSanitizer->sanitize('42', ResourceDefinition::TYPE_INTEGER)->willReturn(42);
        $queryBuilder->andWhere('field IN (:fields)')->shouldBeCalled();
        $queryBuilder->setParameter(':fields', array(42), ResourceDefinition::TYPE_ARRAY)->shouldBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'fields', '42');
    }

    function it_builds_in_statements_with_multiple_values(
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition,
        TypeSanitizer $typeSanitizer
    ) {
        $resourceDefinition->getFieldType('field')->willReturn(ResourceDefinition::TYPE_INTEGER);
        $typeSanitizer->sanitize('42', ResourceDefinition::TYPE_INTEGER)->willReturn(42);
        $typeSanitizer->sanitize('23', ResourceDefinition::TYPE_INTEGER)->willReturn(23);
        $queryBuilder->andWhere('field IN (:fields)')->shouldBeCalled();
        $queryBuilder->setParameter(':fields', array(42, 23), ResourceDefinition::TYPE_ARRAY)->shouldBeCalled();

        $this->build($queryBuilder, $resourceDefinition, 'fields', '42,23');
    }
}
