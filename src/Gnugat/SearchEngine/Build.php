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

use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

class Build
{
    /**
     * @var \Gnugat\SearchEngine\Builder\FilteringBuilder
     */
    private static $filteringBuilder;

    /**
     * @param FilteringBuilderStrategy $filteringBuilderStrategy
     * @param int                      $priority
     */
    public static function addFilteringBuilderStrategy(FilteringBuilderStrategy $filteringBuilderStrategy, $priority = 0)
    {
        self::filteringBuilder()->add($filteringBuilderStrategy, $priority);
    }

    /**
     * @return CriteriaFactory
     */
    public static function criteriaFactory()
    {
        return new CriteriaFactory(
            new \Gnugat\SearchEngine\Criteria\EmbedingFactory(),
            new \Gnugat\SearchEngine\Criteria\FilteringFactory(),
            new \Gnugat\SearchEngine\Criteria\OrderingsFactory(),
            new \Gnugat\SearchEngine\Criteria\PaginatingFactory()
        );
    }

    /**
     * @param Fetcher $fetcher
     *
     * @return SearchEngine
     */
    public static function searchEngine(Fetcher $fetcher)
    {
        return new SearchEngine(
            self::filteringBuilder(),
            new \Gnugat\SearchEngine\Builder\OrderingsBuilder(),
            new \Gnugat\SearchEngine\Builder\PaginatingBuilder(),
            new \Gnugat\SearchEngine\Builder\QueryBuilderFactory($fetcher)
        );
    }

    /**
     * @return \Gnugat\SearchEngine\Builder\FilteringBuilder
     */
    private static function filteringBuilder()
    {
        if (null === self::$filteringBuilder) {
            self::$filteringBuilder = new \Gnugat\SearchEngine\Builder\FilteringBuilder();
            $typeSanitizer = new \Gnugat\SearchEngine\Service\TypeSanitizer();
            self::$filteringBuilder->add(
                new \Gnugat\SearchEngine\Builder\FilteringBuilderStrategy\RangeFilteringBuilderStrategy($typeSanitizer),
                30
            );
            self::$filteringBuilder->add(
                new \Gnugat\SearchEngine\Builder\FilteringBuilderStrategy\StrictComparisonFilteringBuilderStrategy($typeSanitizer),
                10
            );
        }

        return self::$filteringBuilder;
    }
}
