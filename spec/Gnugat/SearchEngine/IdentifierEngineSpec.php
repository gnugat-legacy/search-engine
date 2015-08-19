<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine;

use Gnugat\SearchEngine\Build;
use Gnugat\SearchEngine\Builder\FilteringBuilder;
use Gnugat\SearchEngine\Builder\SelectBuilder;
use Gnugat\SearchEngine\QueryBuilder;
use Gnugat\SearchEngine\QueryBuilderFactory;
use Gnugat\SearchEngine\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class IdentifierEngineSpec extends ObjectBehavior
{
    const RESOURCE_NAME = 'resource';
    const ID = 42;

    function let(FilteringBuilder $filteringBuilder, QueryBuilderFactory $queryBuilderFactory)
    {
        $this->beConstructedWith($filteringBuilder, $queryBuilderFactory);
    }

    function it_fails_if_the_resource_is_not_supported()
    {
        $criteria = Build::criteriaFactory()->fromQueryParameters(self::RESOURCE_NAME, array(
            'id' => self::ID,
        ));

        $noMatchException = 'Gnugat\SearchEngine\NoMatchException';
        $this->shouldThrow($noMatchException)->duringMatch($criteria);
    }

    function it_fails_if_nothing_match(
        FilteringBuilder $filteringBuilder,
        QueryBuilder $queryBuilder,
        QueryBuilderFactory $queryBuilderFactory,
        ResourceDefinition $resourceDefinition,
        SelectBuilder $selectBuilder
    ) {
        $criteria = Build::criteriaFactory()->fromQueryParameters(self::RESOURCE_NAME, array(
            'id' => self::ID,
        ));

        $queryBuilderFactory->make()->willReturn($queryBuilder);
        $selectBuilder->build($queryBuilder, $resourceDefinition, $criteria)->shouldBeCalled();
        $queryBuilder->from(self::RESOURCE_NAME)->shouldBeCalled();
        $filteringBuilder->build($queryBuilder, $resourceDefinition, $criteria->getFiltering())->shouldBeCalled();
        $queryBuilder->execute()->willReturn(json_encode(array()));

        $this->add(self::RESOURCE_NAME, $resourceDefinition, $selectBuilder);
        $noMatchException = 'Gnugat\SearchEngine\NoMatchException';
        $this->shouldThrow($noMatchException)->duringMatch($criteria);
    }

    function it_paginates_matching_results(
        FilteringBuilder $filteringBuilder,
        QueryBuilder $queryBuilder,
        QueryBuilderFactory $queryBuilderFactory,
        ResourceDefinition $resourceDefinition,
        SelectBuilder $selectBuilder
    ) {
        $criteria = Build::criteriaFactory()->fromQueryParameters(self::RESOURCE_NAME, array(
            'id' => self::ID,
        ));
        $item = array('id' => self::ID);

        $queryBuilderFactory->make()->willReturn($queryBuilder);
        $selectBuilder->build($queryBuilder, $resourceDefinition, $criteria)->shouldBeCalled();
        $queryBuilder->from(self::RESOURCE_NAME)->shouldBeCalled();
        $filteringBuilder->build($queryBuilder, $resourceDefinition, $criteria->getFiltering())->shouldBeCalled();
        $queryBuilder->execute()->willReturn(json_encode(array($item)));

        $this->add(self::RESOURCE_NAME, $resourceDefinition, $selectBuilder);
        $this->match($criteria)->shouldBe(json_encode($item));
    }
}
