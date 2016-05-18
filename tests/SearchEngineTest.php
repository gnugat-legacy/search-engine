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

use Gnugat\SearchEngine\Criteria;
use Gnugat\SearchEngine\Test\ArrayBuilder\{
    OrderingBuilder,
    ProfileNameFilteringBuilder,
    SelectProfileBuilder
};
use Gnugat\SearchEngine\Test\ArrayImplementation\ArraySearchEngine;

class SearchEngineTest extends \PHPUnit_Framework_TestCase
{
    const DATA = [
        'profile' => [
            [
                'id' => '168ac7f8-c918-4e99-90ee-5d7590fe61ce',
                'name' => 'Arthur Dent',
            ],
            [
                'id' => 'a426aef2-19e2-412a-8339-5458cf6ae416',
                'name' => 'Ford Prefect',
            ],
            [
                'id' => 'e3ad45ee-7cae-4cca-bd7b-2eb6b57b6457',
                'name' => 'Trillian Astra',
            ],
        ],
    ];

    private $searchEngine;

    protected function setUp()
    {
        $this->searchEngine = new ArraySearchEngine(self::DATA);

        $this->searchEngine->add(new SelectProfileBuilder(), 500);
        $this->searchEngine->add(new OrderingBuilder());
        $this->searchEngine->add(new ProfileNameFilteringBuilder());
    }

    /**
     * @test
     */
    public function it_can_order()
    {
        $criteria = Criteria::fromQueryParameters('profile', [
            'sort' => '-id',
        ]);
        $result = $this->searchEngine->match($criteria);
        $orderedProfiles = iterator_to_array($result->getIterator());

        self::assertSame(
            [
                [
                    'id' => 'e3ad45ee-7cae-4cca-bd7b-2eb6b57b6457',
                    'name' => 'Trillian Astra',
                ],
                [
                    'id' => 'a426aef2-19e2-412a-8339-5458cf6ae416',
                    'name' => 'Ford Prefect',
                ],
                [
                    'id' => '168ac7f8-c918-4e99-90ee-5d7590fe61ce',
                    'name' => 'Arthur Dent',
                ],
            ],
            $orderedProfiles
        );
    }

    /**
     * @test
     */
    public function it_can_filter()
    {
        $criteria = Criteria::fromQueryParameters('profile', [
            'name' => 'Trillian Astra',
        ]);
        $result = $this->searchEngine->match($criteria);
        $filteredProfiles = iterator_to_array($result->getIterator());

        self::assertSame(
            [
                [
                    'id' => 'e3ad45ee-7cae-4cca-bd7b-2eb6b57b6457',
                    'name' => 'Trillian Astra',
                ],
            ],
            $filteredProfiles
        );
    }

    /**
     * @test
     */
    public function it_can_paginate()
    {
        $criteria = Criteria::fromQueryParameters('profile', [
            'page' => 2,
            'per_page' => 2,
        ]);
        $page = $this->searchEngine->match($criteria)->take(
            $criteria->paginating->offset,
            $criteria->paginating->itemsPerPage
        );
        $paginatedProfiles = iterator_to_array($page->getIterator());

        self::assertSame(
            [
                [
                    'id' => 'e3ad45ee-7cae-4cca-bd7b-2eb6b57b6457',
                    'name' => 'Trillian Astra',
                ],
            ],
            $paginatedProfiles
        );
    }
}
