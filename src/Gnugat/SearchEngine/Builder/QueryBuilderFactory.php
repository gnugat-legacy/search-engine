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

use Gnugat\SearchEngine\Fetcher;

class QueryBuilderFactory
{
    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @param Fetcher $fetcher
     */
    public function __construct(Fetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @return QueryBuilder
     */
    public function make()
    {
        return new QueryBuilder($this->fetcher);
    }
}
