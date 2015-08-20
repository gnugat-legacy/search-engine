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

class PaginatingFactorySpec extends ObjectBehavior
{
    const CURRENT_PAGE = 7;
    const ITEMS_PER_PAGE = 14;

    function it_defaults_to_first_page()
    {
        $paginating = $this->fromQueryParameters(array(
        ));

        $paginating->getCurrentPage()->shouldBe(Paginating::DEFAULT_CURRENT_PAGE);
        $paginating->getItemsPerPage()->shouldBe(Paginating::DEFAULT_ITEMS_PER_PAGE);
    }

    function it_extracts_current_page_and_items_per_page_from_query_parameters()
    {
        $paginating = $this->fromQueryParameters(array(
            'page' => self::CURRENT_PAGE,
            'per_page' => self::ITEMS_PER_PAGE,
        ));

        $paginating->getCurrentPage()->shouldBe(self::CURRENT_PAGE);
        $paginating->getItemsPerPage()->shouldBe(self::ITEMS_PER_PAGE);
    }
}
