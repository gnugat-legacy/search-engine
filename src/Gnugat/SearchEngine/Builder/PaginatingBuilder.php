<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Criteria\Paginating;

class PaginatingBuilder
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param Paginating   $paginating
     */
    public function build(QueryBuilder $queryBuilder, Paginating $paginating)
    {
        $queryBuilder->offset($paginating->getOffset());
        $queryBuilder->limit($paginating->getItemsPerPage());
    }
}
