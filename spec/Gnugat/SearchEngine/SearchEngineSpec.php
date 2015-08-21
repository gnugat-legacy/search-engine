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
use Gnugat\SearchEngine\Builder\OrderingsBuilder;
use Gnugat\SearchEngine\Builder\PaginatingBuilder;
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\Builder\QueryBuilderFactory;
use Gnugat\SearchEngine\Builder\SelectBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class SearchEngineSpec extends ObjectBehavior
{
    const RESOURCE_NAME = 'resource';

    const CURRENT_PAGE = 2;
    const PER_PAGE = 3;
    const TOTAL_ELEMENTS = 4;
    const TOTAL_PAGES = 2;

    function let(
        FilteringBuilder $filteringBuilder,
        OrderingsBuilder $orderingsBuilder,
        PaginatingBuilder $paginatingBuilder,
        QueryBuilderFactory $queryBuilderFactory
    ) {
        $this->beConstructedWith($filteringBuilder, $orderingsBuilder, $paginatingBuilder, $queryBuilderFactory);
    }

    function it_returns_an_empty_result_if_the_resource_is_not_supported()
    {
        $criteria = Build::criteriaFactory()->fromQueryParameters(self::RESOURCE_NAME, array(
            'page' => self::CURRENT_PAGE,
            'per_page' => self::PER_PAGE,
        ));

        $this->match($criteria)->shouldBe(json_encode(array(
            'items' => array(),
            'page' => array(
                'current_page' => self::CURRENT_PAGE,
                'per_page' => self::PER_PAGE,
                'total_elements' => 0,
                'total_pages' => 0,
            ),
        )));
    }

    function it_paginates_matching_results(
        FilteringBuilder $filteringBuilder,
        OrderingsBuilder $orderingsBuilder,
        PaginatingBuilder $paginatingBuilder,
        QueryBuilder $queryBuilder,
        QueryBuilderFactory $queryBuilderFactory,
        ResourceDefinition $resourceDefinition,
        SelectBuilder $selectBuilder
    ) {
        $criteria = Build::criteriaFactory()->fromQueryParameters(self::RESOURCE_NAME, array(
            'page' => self::CURRENT_PAGE,
            'per_page' => self::PER_PAGE,
        ));
        $items = array(array('id' => 42));
        $countParameters = array();
        $countParameterTypes = array();
        $countResult = array('total' => self::TOTAL_ELEMENTS);

        $queryBuilderFactory->make()->willReturn($queryBuilder);
        $queryBuilder->from(self::RESOURCE_NAME)->shouldBeCalled();
        $filteringBuilder->build($queryBuilder, $resourceDefinition, $criteria->getFiltering())->shouldBeCalled();

        $queryBuilder->addSelect('COUNT(id) AS total')->shouldBeCalled();
        $queryBuilder->fetchFirst()->willReturn(json_encode($countResult));

        $queryBuilder->resetSelect()->shouldBeCalled();
        $selectBuilder->build($queryBuilder, $resourceDefinition, $criteria)->shouldBeCalled();
        $paginatingBuilder->build($queryBuilder, $criteria->getPaginating())->shouldBeCalled();
        $orderingsBuilder->build($queryBuilder, $resourceDefinition, $criteria->getOrderings())->shouldBeCalled();
        $queryBuilder->fetchAll()->willReturn(json_encode($items));

        $this->add(self::RESOURCE_NAME, $resourceDefinition, $selectBuilder);
        $this->match($criteria)->shouldBe(json_encode(array(
            'items' => $items,
            'page' => array(
                'current_page' => self::CURRENT_PAGE,
                'per_page' => self::PER_PAGE,
                'total_elements' => self::TOTAL_ELEMENTS,
                'total_pages' => self::TOTAL_PAGES,
            ),
        )));
    }
}
