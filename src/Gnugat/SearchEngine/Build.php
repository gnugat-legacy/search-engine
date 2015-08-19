<?php

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
