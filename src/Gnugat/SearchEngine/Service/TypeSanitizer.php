<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Service;

use Gnugat\SearchEngine\ResourceDefinition;

class TypeSanitizer
{
    /**
     * @param string $value
     * @param mixed  $type
     *
     * @return mixed
     */
    public function sanitize($value, $type)
    {
        if (ResourceDefinition::TYPE_INTEGER === $type) {
            return (int) $value;
        }
        if (ResourceDefinition::TYPE_BOOLEAN === $type) {
            return 'false' === $value ? false : (bool) $value;
        }

        return $value;
    }
}
