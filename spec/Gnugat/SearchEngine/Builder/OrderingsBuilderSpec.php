<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Criteria\Ordering;
use Gnugat\SearchEngine\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderingsBuilderSpec extends ObjectBehavior
{
    const FIELD = 'field';
    const DIRECTION = 'DESC';

    function it_sets_orderings(
        Ordering $ordering,
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $ordering->getField()->willReturn(self::FIELD);
        $resourceDefinition->hasField(self::FIELD)->willReturn(true);
        $ordering->getDirection()->willReturn(self::DIRECTION);

        $queryBuilder->addOrderBy(self::FIELD, self::DIRECTION)->shouldBeCalled();

        $this->build($queryBuilder, $resourceDefinition, array($ordering));
    }

    function it_skips_orderings_if_resource_does_not_have_field(
        Ordering $ordering,
        QueryBuilder $queryBuilder,
        ResourceDefinition $resourceDefinition
    ) {
        $ordering->getField()->willReturn(self::FIELD);
        $resourceDefinition->hasField(self::FIELD)->willReturn(false);

        $queryBuilder->addOrderBy(self::FIELD, Argument::any())->shouldNotBeCalled();

        $this->build($queryBuilder, $resourceDefinition, array($ordering));
    }
}
