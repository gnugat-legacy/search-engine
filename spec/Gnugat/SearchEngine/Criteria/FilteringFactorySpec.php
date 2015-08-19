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

class FilteringFactorySpec extends ObjectBehavior
{
    function it_is_fine_without_any_fields()
    {
        $filtering = $this->fromQueryParameters(array(
        ));

        $filtering->getFields()->shouldBe(array());
    }

    function it_does_not_extract_parasite_parameters_as_fields()
    {
        $filtering = $this->fromQueryParameters(array(
            'embed' => 'one,two',
            'page' => 1,
            'per_page' => 2,
            'sort' => 'name,-id',
        ));

        $filtering->getFields()->shouldBe(array());
    }

    function it_extracts_fields_from_query_parameters()
    {
        $filtering = $this->fromQueryParameters(array(
            'field_1' => 'value 1',
            'field_2' => 'value 2',
        ));

        $filtering->getFields()->shouldBe(array(
            'field_1' => 'value 1',
            'field_2' => 'value 2',
        ));
    }
}
