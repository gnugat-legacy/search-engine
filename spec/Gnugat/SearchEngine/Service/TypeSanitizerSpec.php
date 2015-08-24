<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Service;

use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Service\TypeSanitizer;
use PhpSpec\ObjectBehavior;

class TypeSanitizerSpec extends ObjectBehavior
{
    function it_sanitizes_booleans()
    {
        $this->sanitize(true, ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize(false, ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);

        $this->sanitize(-23, ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize(42, ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize(0, ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);

        $this->sanitize('true', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize('1', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize('hello', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize('false', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);
        $this->sanitize('', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);
        $this->sanitize('0', ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);

        $this->sanitize(array(), ResourceDefinition::TYPE_BOOLEAN)->shouldBe(false);
        $this->sanitize(array('hello'), ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
        $this->sanitize(array(array()), ResourceDefinition::TYPE_BOOLEAN)->shouldBe(true);
    }

    function it_sanitizes_integers()
    {
        $this->sanitize(42, ResourceDefinition::TYPE_INTEGER)->shouldBe(42);

        $this->sanitize(TypeSanitizer::MAXIMUM_INTEGER, ResourceDefinition::TYPE_INTEGER)->shouldBe(TypeSanitizer::MAXIMUM_INTEGER);
        $this->sanitize(TypeSanitizer::MAXIMUM_INTEGER + 1, ResourceDefinition::TYPE_INTEGER)->shouldBe(TypeSanitizer::MAXIMUM_INTEGER);
        $this->sanitize(TypeSanitizer::MINIMUM_INTEGER, ResourceDefinition::TYPE_INTEGER)->shouldBe(TypeSanitizer::MINIMUM_INTEGER);
        $this->sanitize(TypeSanitizer::MINIMUM_INTEGER - 1, ResourceDefinition::TYPE_INTEGER)->shouldBe(TypeSanitizer::MINIMUM_INTEGER);

        $this->sanitize('42', ResourceDefinition::TYPE_INTEGER)->shouldBe(42);
        $this->sanitize('42.3', ResourceDefinition::TYPE_INTEGER)->shouldBe(42);
        $this->sanitize('42a', ResourceDefinition::TYPE_INTEGER)->shouldBe(42);
        $this->sanitize('a', ResourceDefinition::TYPE_INTEGER)->shouldBe(0);

        $this->sanitize(true, ResourceDefinition::TYPE_INTEGER)->shouldBe(1);
        $this->sanitize(false, ResourceDefinition::TYPE_INTEGER)->shouldBe(0);

        $this->sanitize(array(), ResourceDefinition::TYPE_INTEGER)->shouldBe(0);
        $this->sanitize(array(42), ResourceDefinition::TYPE_INTEGER)->shouldBe(1);
        $this->sanitize(array(23, 42), ResourceDefinition::TYPE_INTEGER)->shouldBe(1);
    }
}
