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

class Paginating
{
    const DEFAULT_CURRENT_PAGE = 1;
    const DEFAULT_ITEMS_PER_PAGE = 10;

    public $currentPage;
    public $itemsPerPage;
    public $offset;

    public function __construct($currentPage, $itemsPerPage)
    {
        $this->currentPage = (int) $currentPage;
        if ($this->currentPage <= 0) {
            $this->currentPage = self::DEFAULT_CURRENT_PAGE;
        }
        $this->itemsPerPage = (int) $itemsPerPage;
        if ($this->itemsPerPage <= 0) {
            $this->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
        }
        $this->offset = $this->currentPage * $this->itemsPerPage - $this->itemsPerPage;
    }

    public static function fromQueryParameters(array $queryParameters) : self
    {
        $currentPage = $queryParameters['page'] ?? self::DEFAULT_CURRENT_PAGE;
        $maximumResults = $queryParameters['per_page'] ?? self::DEFAULT_ITEMS_PER_PAGE;

        return new self($currentPage, $maximumResults);
    }
}
