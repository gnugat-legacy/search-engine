<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Fetcher;
use PhpSpec\ObjectBehavior;

class QueryBuilderFactorySpec extends ObjectBehavior
{
    function let(Fetcher $fetcher)
    {
        $this->beConstructedWith($fetcher);
    }

    function it_makes_query_builders()
    {
        $this->make()->shouldHaveType('Gnugat\SearchEngine\Builder\QueryBuilder');
    }
}
