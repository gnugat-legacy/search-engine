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
    const MINIMUM_INTEGER = -2147483648;
    const MAXIMUM_INTEGER = 2147483647;

    /**
     * @param string $value
     * @param string $type
     *
     * @return mixed
     */
    public function sanitize($value, $type)
    {
        if (ResourceDefinition::TYPE_INTEGER === $type) {
            $integer = (int) $value;
            if (self::MINIMUM_INTEGER > $integer) {
                return self::MINIMUM_INTEGER;
            }
            if (self::MAXIMUM_INTEGER < $integer) {
                return self::MAXIMUM_INTEGER;
            }

            return $integer;
        }
        if (ResourceDefinition::TYPE_BOOLEAN === $type) {
            return 'false' === $value ? false : (bool) $value;
        }

        return $value;
    }
}
