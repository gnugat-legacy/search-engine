<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine;

interface QueryBuilder
{
    /**
     * @param mixed $select A string (e.g. `COUNT(id) AS total`) or an array of fields (e.g. `['id', 'name']`)
     */
    public function select($select);

    /**
     * @param string $from
     * @param string $alias
     */
    public function from($resource, $alias = null);

    /**
     * @return \Traversable Depending on the implementation, could be an array or an iterator.
     */
    public function execute();
}
