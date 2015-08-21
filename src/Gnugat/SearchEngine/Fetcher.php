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

interface Fetcher
{
    /**
     * @param string $sql
     * @param array  $parameters
     *
     * @return mixed
     */
    public function fetchAll($sql, array $parameters);

    /**
     * @param string $sql
     * @param array  $parameters
     *
     * @return mixed
     */
    public function fetchFirst($sql, array $parameters);
}
