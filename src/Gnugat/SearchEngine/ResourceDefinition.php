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

interface ResourceDefinition
{
    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasField($field);

    /**
     * @param string $field
     *
     * @return string
     */
    public function getFieldType($field);
}
