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
use Gnugat\SearchEngine\Criteria\Filtering;
use Gnugat\SearchEngine\Criteria\Orderings;
use Gnugat\SearchEngine\Criteria\Paginating;
use PhpSpec\ObjectBehavior;

class CriteriaSpec extends ObjectBehavior
{
    const RESOURCE_NAME = 'resource';

    function let(
        Embeding $embeding,
        Filtering $filtering,
        Orderings $orderings,
        Paginating $paginating
    ) {
        $this->beConstructedWith(
            self::RESOURCE_NAME,
            $embeding,
            $filtering,
            $orderings,
            $paginating
        );
    }

    function it_has_resource_name()
    {
        $this->resourceName->shouldBe(self::RESOURCE_NAME);
    }

    function it_has_embeding(Embeding $embeding)
    {
        $this->embeding->shouldBe($embeding);
    }

    function it_has_filtering(Filtering $filtering)
    {
        $this->filtering->shouldBe($filtering);
    }

    function it_has_orderings(Orderings $orderings)
    {
        $this->orderings->shouldBe($orderings);
    }

    function it_has_paginating(Paginating $paginating)
    {
        $this->paginating->shouldBe($paginating);
    }
}
