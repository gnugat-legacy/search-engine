<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test\ArrayImplementation;

use Porpaginas\Arrays\ArrayResult;
use Porpaginas\Result;
use Gnugat\SearchEngine\{Criteria, SearchEngine};

class ArraySearchEngine implements SearchEngine
{
    private $builders = [];
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function add(Builder $builder, int $priority = 0)
    {
        $this->builders[$priority][] = $builder;
    }

    public function match(Criteria $criteria) : Result
    {
        $data = $this->data;
        foreach ($this->builders as $priority => $builders) {
            foreach ($builders as $builder) {
                if (true === $builder->supports($criteria)) {
                    $data = $builder->build($criteria, $data);
                }
            }
        }

        return new ArrayResult($data);
    }
}
