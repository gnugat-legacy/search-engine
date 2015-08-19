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

use Gnugat\SearchEngine\Criteria\EmbedingFactory;
use Gnugat\SearchEngine\Criteria\FilteringFactory;
use Gnugat\SearchEngine\Criteria\OrderingsFactory;
use Gnugat\SearchEngine\Criteria\PaginatingFactory;

class Build
{
    /**
     * @return CriteriaFactory
     */
    public static function criteriaFactory()
    {
        return new CriteriaFactory(
            new EmbedingFactory(),
            new FilteringFactory(),
            new OrderingsFactory(),
            new PaginatingFactory()
        );
    }
}
