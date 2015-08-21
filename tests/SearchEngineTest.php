<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test;

use Gnugat\SearchEngine\Build;
use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy\RegexFilteringBuilderStrategy;
use Gnugat\SearchEngine\Criteria\Paginating;
use Gnugat\SearchEngine\Test\Fixtures\Project\ProjectBuild;
use PHPUnit_Framework_TestCase;

class SearchEngineTest extends PHPUnit_Framework_TestCase
{
    private $criteriaFactory;
    private $searchEngine;

    protected function setUp()
    {
        $fetcher = ProjectBuild::fetcher();
        $blogResourceDefinition = ProjectBuild::blogResourceDefinition();
        $blogSelectBuilder = ProjectBuild::blogSelectBuilder();

        Build::addFilteringBuilderStrategy(new RegexFilteringBuilderStrategy(), 20);
        $this->criteriaFactory = Build::criteriaFactory();
        $this->searchEngine = Build::searchEngine($fetcher);
        $this->searchEngine->add('blog', $blogResourceDefinition, $blogSelectBuilder);
    }

    /**
     * @test
     */
    public function it_can_find_all_results()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array());
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 1,
                    'title' => 'Big Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 2,
                    'title' => 'Big Header',
                    'author_id' => 2,
                ),
                array(
                    'id' => 3,
                    'title' => 'Ancient Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 4,
                    'title' => 'Ancient Header',
                    'author_id' => 3,
                ),
            ),
            'page' => array(
                'current_page' => Paginating::DEFAULT_CURRENT_PAGE,
                'per_page' => Paginating::DEFAULT_ITEMS_PER_PAGE,
                'total_elements' => 4,
                'total_pages' => 1,
            ),
        ), $results);
    }

    /**
     * @test
     */
    public function it_can_whitelist()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('author_ids' => '1,3'));
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 1,
                    'title' => 'Big Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 3,
                    'title' => 'Ancient Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 4,
                    'title' => 'Ancient Header',
                    'author_id' => 3,
                ),
            ),
            'page' => array(
                'current_page' => Paginating::DEFAULT_CURRENT_PAGE,
                'per_page' => Paginating::DEFAULT_ITEMS_PER_PAGE,
                'total_elements' => 3,
                'total_pages' => 1,
            ),
        ), $results);
    }

    /**
     * @test
     */
    public function it_can_filter_using_case_insensitive_regex()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('title' => 'IG'));
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 1,
                    'title' => 'Big Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 2,
                    'title' => 'Big Header',
                    'author_id' => 2,
                ),
            ),
            'page' => array(
                'current_page' => Paginating::DEFAULT_CURRENT_PAGE,
                'per_page' => Paginating::DEFAULT_ITEMS_PER_PAGE,
                'total_elements' => 2,
                'total_pages' => 1,
            ),
        ), $results);
    }

    /**
     * @test
     */
    public function it_can_paginate()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('page' => 2, 'per_page' => 1));
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 2,
                    'title' => 'Big Header',
                    'author_id' => 2,
                ),
            ),
            'page' => array(
                'current_page' => 2,
                'per_page' => 1,
                'total_elements' => 4,
                'total_pages' => 4,
            ),
        ), $results);
    }

    /**
     * @test
     */
    public function it_can_order()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('sort' => 'author_id,-title'));
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 1,
                    'title' => 'Big Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 3,
                    'title' => 'Ancient Title',
                    'author_id' => 1,
                ),
                array(
                    'id' => 2,
                    'title' => 'Big Header',
                    'author_id' => 2,
                ),
                array(
                    'id' => 4,
                    'title' => 'Ancient Header',
                    'author_id' => 3,
                ),
            ),
            'page' => array(
                'current_page' => Paginating::DEFAULT_CURRENT_PAGE,
                'per_page' => Paginating::DEFAULT_ITEMS_PER_PAGE,
                'total_elements' => 4,
                'total_pages' => 1,
            ),
        ), $results);
    }

    /**
     * @test
     */
    public function it_can_embed_relations()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('embed' => 'author'));
        $results = $this->searchEngine->match($criteria);

        self::assertSame(array(
            'items' => array(
                array(
                    'id' => 1,
                    'title' => 'Big Title',
                    'author' => json_encode(array(
                        'id' => 1,
                        'name' => 'Nate',
                    )),
                ),
                array(
                    'id' => 2,
                    'title' => 'Big Header',
                    'author' => json_encode(array(
                        'id' => 2,
                        'name' => 'Nicolas',
                    )),
                ),
                array(
                    'id' => 3,
                    'title' => 'Ancient Title',
                    'author' => json_encode(array(
                        'id' => 1,
                        'name' => 'Nate',
                    )),
                ),
                array(
                    'id' => 4,
                    'title' => 'Ancient Header',
                    'author' => json_encode(array(
                        'id' => 3,
                        'name' => 'Lorel',
                    )),
                ),
            ),
            'page' => array(
                'current_page' => Paginating::DEFAULT_CURRENT_PAGE,
                'per_page' => Paginating::DEFAULT_ITEMS_PER_PAGE,
                'total_elements' => 4,
                'total_pages' => 1,
            ),
        ), $results);
    }
}
