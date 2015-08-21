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

use Gnugat\SearchEngine\Criteria\OrderingsFactory;
use PhpSpec\ObjectBehavior;

class OrderingsFactorySpec extends ObjectBehavior
{
    function it_defaults_to_id_ordering()
    {
        $orderings = $this->fromQueryParameters(array(
        ));

        $orderings->shouldHaveCount(1);

        $ordering = $orderings[0];
        $ordering->getField()->shouldBe(OrderingsFactory::DEFAULT_FIELD);
        $ordering->getDirection()->shouldBe(OrderingsFactory::DEFAULT_DIRECTION);
    }

    function it_extracts_one_ascending_ordering()
    {
        $orderings = $this->fromQueryParameters(array(
            'sort' => 'field1',
        ));

        $orderings->shouldHaveCount(1);

        $ordering = $orderings[0];
        $ordering->getField()->shouldBe('field1');
        $ordering->getDirection()->shouldBe('ASC');
    }

    function it_extracts_one_descending_ordering()
    {
        $orderings = $this->fromQueryParameters(array(
            'sort' => '-field1',
        ));

        $orderings->shouldHaveCount(1);

        $ordering = $orderings[0];
        $ordering->getField()->shouldBe('field1');
        $ordering->getDirection()->shouldBe('DESC');
    }

    function it_extracts_many_mixed_orderings()
    {
        $orderings = $this->fromQueryParameters(array(
            'sort' => 'field1,-field2',
        ));

        $orderings->shouldHaveCount(2);

        $field1Ordering = $orderings[0];
        $field1Ordering->getField()->shouldBe('field1');
        $field1Ordering->getDirection()->shouldBe('ASC');

        $field2Ordering = $orderings[1];
        $field2Ordering->getField()->shouldBe('field2');
        $field2Ordering->getDirection()->shouldBe('DESC');
    }
}
