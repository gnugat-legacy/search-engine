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
        $this->getFields()->shouldBe(array(self::COLUMN_1 => self::VALUE_1));
    }

    function it_can_have_more_fields()
    {
        $this->addField(self::COLUMN_2, self::VALUE_2);
        $this->getFields()->shouldBe(array(
            self::COLUMN_1 => self::VALUE_1,
            self::COLUMN_2 => self::VALUE_2,
        ));
    }
}
