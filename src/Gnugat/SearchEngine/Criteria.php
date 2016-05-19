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

use Gnugat\SearchEngine\Criteria\{
    Embeding,
    Filtering,
    Orderings,
    Paginating
};

class Criteria
{
    public $resourceName;
    public $embeding;
    public $filtering;
    public $orderings;
    public $paginating;

    public function __construct(
        string $resourceName,
        Embeding $embeding,
        Filtering $filtering,
        Orderings $orderings,
        Paginating $paginating
    ) {
        $this->resourceName = $resourceName;
        $this->embeding = $embeding;
        $this->filtering = $filtering;
        $this->orderings = $orderings;
        $this->paginating = $paginating;
    }

    public static function fromQueryParameters(string $resourceName, array $queryParameters) : self
    {
        return new self(
            $resourceName,
            Embeding::fromQueryParameters($queryParameters),
            Filtering::fromQueryParameters($queryParameters),
            Orderings::fromQueryParameters($queryParameters),
            Paginating::fromQueryParameters($queryParameters)
        );
    }
}
