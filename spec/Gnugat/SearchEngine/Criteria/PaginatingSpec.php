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

use Gnugat\SearchEngine\Criteria\Paginating;
use PhpSpec\ObjectBehavior;

class PaginatingSpec extends ObjectBehavior
{
    const CURRENT_PAGE = 4;
    const ITEMS_PER_PAGE = 7;
    const OFFSET = 21;

    function let()
    {
        $this->beConstructedWith(self::CURRENT_PAGE, self::ITEMS_PER_PAGE);
    }

    function it_has_current_page()
    {
        $this->getCurrentPage()->shouldBe(self::CURRENT_PAGE);
    }

    function it_forces_current_page_to_be_a_strictly_positive_integer()
    {
        $this->beConstructedWith(-42, self::ITEMS_PER_PAGE);

        $this->getCurrentPage()->shouldBe(Paginating::DEFAULT_CURRENT_PAGE);
    }

    function it_has_offset()
    {
        $this->getOffset()->shouldBe(self::OFFSET);
    }

    function it_has_items_per_page()
    {
        $this->getItemsPerPage()->shouldBe(self::ITEMS_PER_PAGE);
    }

    function it_forces_items_per_page_to_be_a_strictly_positive_integer()
    {
        $this->beConstructedWith(self::CURRENT_PAGE, -42);

        $this->getItemsPerPage()->shouldBe(Paginating::DEFAULT_ITEMS_PER_PAGE);
    }
}
