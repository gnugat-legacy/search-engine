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

class Filtering
{
    public $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public static function fromQueryParameters(array $queryParameters) : self
    {
        $fields = $queryParameters;
        unset($fields['embed']);
        unset($fields['page']);
        unset($fields['per_page']);
        unset($fields['sort']);

        return new self($fields);
    }

    public function add($key, $value)
    {
        $this->fields[$key] = $value;
    }

    public function has($key) : bool
    {
        return isset($this->fields[$key]) && (false === empty($this->fields[$key]));
    }
}
