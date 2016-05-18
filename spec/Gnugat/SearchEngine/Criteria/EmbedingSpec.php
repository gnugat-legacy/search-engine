<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Criteria;

use PhpSpec\ObjectBehavior;

class EmbedingSpec extends ObjectBehavior
{
    const RELATION = 'relation';
    const ANOTHER_RELATION = 'another_relation';

    function let()
    {
        $relations = [self::RELATION];

        $this->beConstructedWith($relations);
    }

    function it_has_relations()
    {
        $this->relations->shouldBe([self::RELATION]);
    }

    function it_ignores_query_parameters_without_relations()
    {
        $this->beConstructedFromQueryParameters([]);

        $this->relations->shouldBe([]);
    }

    function it_extracts_one_relation_from_query_parameters()
    {
        $this->beConstructedFromQueryParameters([
            'embed' => 'relation',
        ]);

        $this->relations->shouldBe(['relation']);
    }

    function it_extracts_many_relations_from_query_parameters()
    {
        $this->beConstructedFromQueryParameters([
            'embed' => 'one,two,three',
        ]);

        $this->relations->shouldBe(['one', 'two', 'three']);
    }
}
