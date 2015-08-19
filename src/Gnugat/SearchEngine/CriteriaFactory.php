<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine;

use Gnugat\SearchEngine\Criteria\EmbedingFactory;
use Gnugat\SearchEngine\Criteria\FilteringFactory;
use Gnugat\SearchEngine\Criteria\OrderingsFactory;
use Gnugat\SearchEngine\Criteria\PaginatingFactory;

class CriteriaFactory
{
    /**
     * @var EmbedingFactory
     */
    private $embedingFactory;

    /**
     * @var FilteringFactory
     */
    private $filteringFactory;

    /**
     * @var OrderingsFactory
     */
    private $orderingsFactory;

    /**
     * @var PaginatingFactory
     */
    private $paginatingFactory;

    /**
     * @param EmbedingFactory   $embedingFactory
     * @param FilteringFactory  $filteringFactory
     * @param OrderingsFactory  $orderingsFactory
     * @param PaginatingFactory $paginatingFactory
     */
    public function __construct(
        EmbedingFactory $embedingFactory,
        FilteringFactory $filteringFactory,
        OrderingsFactory $orderingsFactory,
        PaginatingFactory $paginatingFactory
    ) {
        $this->embedingFactory = $embedingFactory;
        $this->filteringFactory = $filteringFactory;
        $this->orderingsFactory = $orderingsFactory;
        $this->paginatingFactory = $paginatingFactory;
    }

    /**
     * @param string $resourceName
     * @param array  $queryParameters
     *
     * @return Criteria
     */
    public function fromQueryParameters($resourceName, array $queryParameters)
    {
        $embeding = $this->embedingFactory->fromQueryParameters($queryParameters);
        $filtering = $this->filteringFactory->fromQueryParameters($queryParameters);
        $paginating = $this->paginatingFactory->fromQueryParameters($queryParameters);
        $orderings = $this->orderingsFactory->fromQueryParameters($queryParameters);

        return Criteria::forSearchEngine($resourceName, $embeding, $filtering, $paginating, $orderings);
    }
}
