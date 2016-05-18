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
    public $relations;

    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    public static function fromQueryParameters(array $queryParameters) : self
    {
        if (false === isset($queryParameters['embed'])) {
            return new self([]);
        }

        return new self(explode(',', $queryParameters['embed']));
    }

    public function has(string $relation) : bool
    {
        return in_array($relation, $this->relations, true);
    }
}
