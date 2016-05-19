<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test\ArrayBuilder;

use Gnugat\SearchEngine\Test\ArrayImplementation\Builder;
use Gnugat\SearchEngine\Criteria;

class OrderingBuilder implements Builder
{
    public function supports(Criteria $criteria) : bool
    {
        return false === empty($criteria->orderings->orderings);
    }

    public function build(Criteria $criteria, array $data) : array
    {
        $args = [];
        $key = 0;
        foreach ($criteria->orderings->orderings as $ordering) {
            $args[$key] = array_column($data, $ordering->field);
            $args[$key + 1] = $ordering->direction === 'ASC' ? SORT_ASC : SORT_DESC;
            $key += 2;
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);

        return $data;
    }
}
