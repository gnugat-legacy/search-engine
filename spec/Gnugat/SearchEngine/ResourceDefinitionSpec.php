<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceDefinitionSpec extends ObjectBehavior
{
    const NAME = 'resource';
    const FIELD = 'field';
    const TYPE = 'string';
    const RELATION = 'relation';

    function let()
    {
        $this->beConstructedWith(
            self::NAME,
            array(
                self::FIELD => self::TYPE,
            ),
            array(self::RELATION)
        );
    }

    function it_has_a_name()
    {
        $this->getName()->shouldBe(self::NAME);
    }

    function it_has_fields()
    {
        $this->getFields()->shouldBe(array(self::FIELD));
    }

    function it_checks_if_it_has_given_field()
    {
        $this->hasField(self::FIELD)->shouldBe(true);
        $this->hasField('nope')->shouldBe(false);
    }

    function it_gets_the_type_of_the_given_field()
    {
        $this->getFieldType(self::FIELD)->shouldBe(self::TYPE);
    }

    function it_has_relations()
    {
        $this->getRelations()->shouldBe(array(self::RELATION));
    }

    function it_checks_if_it_has_given_relation()
    {
        $this->hasrelation(self::RELATION)->shouldBe(true);
        $this->hasrelation('nope')->shouldBe(false);
    }
}
