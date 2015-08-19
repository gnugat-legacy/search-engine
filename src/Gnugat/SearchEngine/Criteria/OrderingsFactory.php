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

class OrderingsFactory
{
    const DEFAULT_FIELD = 'id';
    const DEFAULT_DIRECTION = 'ASC';

    /**
     * @param array $queryParameters
     *
     * @return array
     */
    public function fromQueryParameters(array $queryParameters)
    {
        $sort = self::DEFAULT_FIELD;
        if (isset($queryParameters['sort'])) {
            $sort = $queryParameters['sort'];
        }
        $columns = explode(',', $sort);
        $orderings = array();
        foreach ($columns as $column) {
            $direction = self::DEFAULT_DIRECTION;
            if ('-' === $column[0]) {
                $direction = 'DESC';
                $column = trim($column, '-');
            }
            $orderings[] = new Ordering($column, $direction);
        }

        return $orderings;
    }
}
