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

class EmbedingFactorySpec extends ObjectBehavior
{
    function it_ignores_query_parameters_without_relations()
    {
        $embeding = $this->fromQueryParameters(array(
        ));

        $embeding->getRelations()->shouldBe(array());
    }

    function it_extracts_one_relation_from_query_parameters()
    {
        $embeding = $this->fromQueryParameters(array(
            'embed' => 'relation',
        ));

        $embeding->getRelations()->shouldBe(array('relation'));
    }

    function it_extracts_many_relations_from_query_parameters()
    {
        $embeding = $this->fromQueryParameters(array(
            'embed' => 'one,two,three',
        ));

        $embeding->getRelations()->shouldBe(array('one', 'two', 'three'));
    }
}
