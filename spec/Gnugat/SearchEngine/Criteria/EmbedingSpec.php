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
        $relations = array(self::RELATION);

        $this->beConstructedWith($relations);
    }

    function it_has_relations()
    {
        $this->getRelations()->shouldBe(array(self::RELATION));
    }
}
