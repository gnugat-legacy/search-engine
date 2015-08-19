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

use Gnugat\SearchEngine\Criteria\Embeding;
use Gnugat\SearchEngine\Criteria\EmbedingFactory;
use Gnugat\SearchEngine\Criteria\Filtering;
use Gnugat\SearchEngine\Criteria\FilteringFactory;
use Gnugat\SearchEngine\Criteria\Ordering;
use Gnugat\SearchEngine\Criteria\OrderingsFactory;
use Gnugat\SearchEngine\Criteria\Paginating;
use Gnugat\SearchEngine\Criteria\PaginatingFactory;
use PhpSpec\ObjectBehavior;

class CriteriaFactorySpec extends ObjectBehavior
{
    const RESOURCE_NAME = 'resource';

    function let(
        EmbedingFactory $embedingFactory,
        FilteringFactory $filteringFactory,
        OrderingsFactory $orderingsFactory,
        PaginatingFactory $paginatingFactory
    ) {
        $this->beConstructedWith($embedingFactory, $filteringFactory, $orderingsFactory, $paginatingFactory);
    }

    function it_creates_criteria_from_query_parameters(
        Embeding $embeding,
        EmbedingFactory $embedingFactory,
        Filtering $filtering,
        FilteringFactory $filteringFactory,
        Ordering $ordering,
        OrderingsFactory $orderingsFactory,
        Paginating $paginating,
        PaginatingFactory $paginatingFactory
    ) {
        $queryParameters = array();

        $embedingFactory->fromQueryParameters($queryParameters)->willReturn($embeding);
        $filteringFactory->fromQueryParameters($queryParameters)->willReturn($filtering);
        $orderingsFactory->fromQueryParameters($queryParameters)->willReturn(array($ordering));
        $paginatingFactory->fromQueryParameters($queryParameters)->willReturn($paginating);

        $criteria = $this->fromQueryParameters(self::RESOURCE_NAME, $queryParameters);
        $criteria->getResourceName()->shouldBe(self::RESOURCE_NAME);
    }
}
