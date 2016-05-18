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

class SelectProfileBuilder implements Builder
{
    public function supports(Criteria $criteria) : bool
    {
        return 'profile' === $criteria->resourceName;
    }

    public function build(Criteria $criteria, array $data) : array
    {
        return $data[$criteria->resourceName];
    }
}
