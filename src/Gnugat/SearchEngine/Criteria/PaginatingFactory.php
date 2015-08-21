<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Criteria;

class PaginatingFactory
{
    /**
     * @param array $queryParameters
     *
     * @return Pagination
     */
    public function fromQueryParameters(array $queryParameters)
    {
        $currentPage = Paginating::DEFAULT_CURRENT_PAGE;
        if (isset($queryParameters['page'])) {
            $currentPage = (int) $queryParameters['page'];
        }
        $maximumResults = Paginating::DEFAULT_ITEMS_PER_PAGE;
        if (isset($queryParameters['per_page'])) {
            $maximumResults = (int) $queryParameters['per_page'];
        }

        return new Paginating($currentPage, $maximumResults);
    }
}
