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

use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\Criteria\Paginating;
use PhpSpec\ObjectBehavior;

class PaginatingBuilderSpec extends ObjectBehavior
{
    const OFFSET = 0;
    const LIMIT = 10;

    function it_sets_offset_and_limit(Paginating $paginating, QueryBuilder $queryBuilder)
    {
        $paginating->getOffset()->willReturn(self::OFFSET);
        $paginating->getItemsPerPage()->willReturn(self::LIMIT);

        $queryBuilder->offset(self::OFFSET)->shouldBeCalled();
        $queryBuilder->limit(self::LIMIT)->shouldBeCalled();

        $this->build($queryBuilder, $paginating);
    }
}
