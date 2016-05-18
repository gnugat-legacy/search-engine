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

class OrderingSpec extends ObjectBehavior
{
    const FIELD = 'field';
    const DIRECTION = 'ASC';

    function let()
    {
        $this->beConstructedWith(self::FIELD, self::DIRECTION);
    }

    function it_has_a_field()
    {
        $this->field->shouldBe(self::FIELD);
    }

    function it_has_a_direction()
    {
        $this->direction->shouldBe(self::DIRECTION);
    }
}
