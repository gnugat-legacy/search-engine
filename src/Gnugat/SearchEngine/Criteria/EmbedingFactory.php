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

class EmbedingFactory
{
    /**
     * @param array $queryParameters
     *
     * @return Embeding
     */
    public function fromQueryParameters(array $queryParameters)
    {
        if (!isset($queryParameters['embed'])) {
            return new Embeding(array());
        }

        return new Embeding(explode(',', $queryParameters['embed']));
    }
}
