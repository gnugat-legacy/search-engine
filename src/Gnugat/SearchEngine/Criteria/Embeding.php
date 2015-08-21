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

class Embeding
{
    /**
     * @var array
     */
    private $relations;

    /**
     * @param array $relations
     */
    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
