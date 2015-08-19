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

class FilteringFactory
{
    /**
     * @param array $queryParameters
     *
     * @return Filtering
     */
    public function fromQueryParameters(array $queryParameters)
    {
        $fields = $queryParameters;
        unset($fields['embed']);
        unset($fields['page']);
        unset($fields['per_page']);
        unset($fields['sort']);

        return new Filtering($fields);
    }
}
