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

class ProfileNameFilteringBuilder implements Builder
{
    public function supports(Criteria $criteria) : bool
    {
        return 'profile' === $criteria->resourceName && isset($criteria->filtering->fields['name']);
    }

    public function build(Criteria $criteria, array $data) : array
    {
        $name = $criteria->filtering->fields['name'];

        return array_values(array_filter($data, function ($profile) use ($name) {
            return $name === $profile['name'];
        }));
    }
}
