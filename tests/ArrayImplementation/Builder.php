<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test\ArrayImplementation;

use Gnugat\SearchEngine\Criteria;

interface Builder
{
    public function supports(Criteria $criteria) : bool;
    public function build(Criteria $criteria, array $data) : array;
}
