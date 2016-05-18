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

use Gnugat\SearchEngine\Criteria\Orderings;
use PhpSpec\ObjectBehavior;

class OrderingsSpec extends ObjectBehavior
{
    function it_defaults_to_id_ordering()
    {
        $this->beConstructedFromQueryParameters([]);

        $this->orderings->shouldHaveCount(1);

        $ordering = $this->orderings[0];
        $ordering->field->shouldBe(Orderings::DEFAULT_FIELD);
        $ordering->direction->shouldBe(Orderings::DEFAULT_DIRECTION);
    }

    function it_extracts_one_ascending_ordering()
    {
        $this->beConstructedFromQueryParameters([
            'sort' => 'field1',
        ]);

        $this->orderings->shouldHaveCount(1);

        $ordering = $this->orderings[0];
        $ordering->field->shouldBe('field1');
        $ordering->direction->shouldBe('ASC');
    }

    function it_extracts_one_descending_ordering()
    {
        $this->beConstructedFromQueryParameters([
            'sort' => '-field1',
        ]);

        $this->orderings->shouldHaveCount(1);

        $ordering = $this->orderings[0];
        $ordering->field->shouldBe('field1');
        $ordering->direction->shouldBe('DESC');
    }

    function it_extracts_many_mixed_orderings()
    {
        $this->beConstructedFromQueryParameters([
            'sort' => 'field1,-field2',
        ]);

        $this->orderings->shouldHaveCount(2);

        $field1Ordering = $this->orderings[0];
        $field1Ordering->field->shouldBe('field1');
        $field1Ordering->direction->shouldBe('ASC');

        $field2Ordering = $this->orderings[1];
        $field2Ordering->field->shouldBe('field2');
        $field2Ordering->direction->shouldBe('DESC');
    }
}
