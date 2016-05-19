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

class Orderings
{
    const DEFAULT_FIELD = 'id';
    const DEFAULT_DIRECTION = 'ASC';

    public $orderings = [];

    public function __construct(array $orderings = [])
    {
        $this->orderings = $orderings;
    }

    public static function fromQueryParameters(array $queryParameters) : self
    {
        $sort = self::DEFAULT_FIELD;
        if (true === isset($queryParameters['sort'])) {
            $sort = $queryParameters['sort'];
        }
        $columns = explode(',', $sort);
        $orderings = new self();
        foreach ($columns as $column) {
            $direction = self::DEFAULT_DIRECTION;
            if ('-' === $column[0]) {
                $direction = 'DESC';
                $column = trim($column, '-');
            }
            $orderings->add(new Ordering($column, $direction));
        }

        return $orderings;
    }

    public function add(Ordering $ordering)
    {
        $this->orderings[] = $ordering;
    }

    public function has(string $field) : bool
    {
        foreach ($this->orderings as $ordering) {
            if ($ordering->field === $field) {
                return true;
            }
        }

        return false;
    }

    public function get(string $field) : Ordering
    {
        foreach ($this->orderings as $ordering) {
            if ($ordering->field === $field) {
                return $ordering;
            }
        }

        return;
    }
}
