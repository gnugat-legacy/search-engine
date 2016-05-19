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

class FilteringSpec extends ObjectBehavior
{
    const COLUMN_1 = 'column_1_1';
    const VALUE_1 = 'value_1';
    const COLUMN_2 = 'column_2';
    const VALUE_2 = 'value_2';

    function let()
    {
        $fields = array(self::COLUMN_1 => self::VALUE_1);

        $this->beConstructedWith($fields);
    }

    function it_has_fields()
    {
        $this->fields->shouldBe([self::COLUMN_1 => self::VALUE_1]);
    }

    function it_can_have_more_fields()
    {
        $this->add(self::COLUMN_2, self::VALUE_2);
        $this->fields->shouldBe([
            self::COLUMN_1 => self::VALUE_1,
            self::COLUMN_2 => self::VALUE_2,
        ]);
    }

    function it_is_fine_without_any_fields()
    {
        $this->beConstructedFromQueryParameters([]);

        $this->fields->shouldBe([]);
    }

    function it_does_not_extract_parasite_parameters_as_fields()
    {
        $this->beConstructedFromQueryParameters([
            'embed' => 'one,two',
            'page' => 1,
            'per_page' => 2,
            'sort' => 'name,-id',
        ]);

        $this->fields->shouldBe([]);
    }

    function it_extracts_fields_from_query_parameters()
    {
        $this->beConstructedFromQueryParameters([
            'field_1' => 'value 1',
            'field_2' => 'value 2',
        ]);

        $this->fields->shouldBe([
            'field_1' => 'value 1',
            'field_2' => 'value 2',
        ]);
    }
}
