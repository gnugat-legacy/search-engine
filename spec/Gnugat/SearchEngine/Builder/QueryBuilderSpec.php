<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Fetcher;
use Gnugat\SearchEngine\ResourceDefinition;
use PhpSpec\ObjectBehavior;

class QueryBuilderSpec extends ObjectBehavior
{
    function let(Fetcher $fetcher)
    {
        $this->beConstructedWith($fetcher);
    }

    function it_can_select_everything(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource', array())->shouldBeCalled();

        $this->from('resource');
        $this->fetchAll();
    }

    function it_can_select_first_element(Fetcher $fetcher)
    {
        $fetcher->fetchFirst('SELECT * FROM resource', array())->shouldBeCalled();

        $this->from('resource');
        $this->fetchFirst();
    }

    function it_can_use_resource_aliases(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource r', array())->shouldBeCalled();

        $this->from('resource r');
        $this->fetchAll();
    }

    function it_can_select_specific_fields(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT id, name FROM resource', array())->shouldBeCalled();

        $this->addSelect('id');
        $this->addSelect('name');
        $this->from('resource');
        $this->fetchAll();
    }

    function it_can_reset_the_select_choice(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource', array())->shouldBeCalled();

        $this->addSelect('id');
        $this->addSelect('name');
        $this->resetSelect();
        $this->from('resource');
        $this->fetchAll();
    }

    function it_can_count_elements(Fetcher $fetcher)
    {
        $fetcher->fetchFirst('SELECT COUNT(id) AS total FROM resource', array())->shouldBeCalled();

        $this->addSelect('COUNT(id) AS total');
        $this->from('resource');
        $this->fetchFirst();
    }

    function it_can_join_relation(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource JOIN relation', array())->shouldBeCalled();

        $this->from('resource');
        $this->addJoin('JOIN', 'relation');
        $this->fetchAll();
    }

    function it_can_join_relations_with_conditions(Fetcher $fetcher)
    {
        $sql = array(
            'SELECT * FROM resource',
            'LEFT JOIN relation_1 ON resource.id = relation_1.resource_id',
            'LEFT JOIN relation_2 ON relation_1.id = relation_2.relation_1_id',
        );
        $fetcher->fetchAll(implode(' ', $sql), array())->shouldBeCalled();

        $this->from('resource');
        $this->addJoin('LEFT JOIN', 'relation_1', 'resource.id = relation_1.resource_id');
        $this->addJoin('LEFT JOIN', 'relation_2', 'relation_1.id = relation_2.relation_1_id');
        $this->fetchAll();
    }

    function it_can_filter_results_with_parameters(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource WHERE name LIKE :name AND id IN (:id_0, :id_1)', array(
            array('name' => ':name', 'value' => 'ban%', 'type' => ResourceDefinition::TYPE_STRING),
            array('name' => ':id_0', 'value' => 1, 'type' => ResourceDefinition::TYPE_INTEGER),
            array('name' => ':id_1', 'value' => 3, 'type' => ResourceDefinition::TYPE_INTEGER),
        ))->shouldBeCalled();

        $this->addWhere('name LIKE :name');
        $this->addParameter(':name', 'ban%', ResourceDefinition::TYPE_STRING);
        $this->addWhere('id IN (:id_0, :id_1)');
        $this->addParameter(':id_0', 1, ResourceDefinition::TYPE_INTEGER);
        $this->addParameter(':id_1', 3, ResourceDefinition::TYPE_INTEGER);
        $this->from('resource');
        $this->fetchAll();
    }

    function it_can_order_results(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource ORDER BY name ASC, relation_id DESC', array())->shouldBeCalled();

        $this->addOrderBy('name', 'ASC');
        $this->addOrderBy('relation_id', 'DESC');
        $this->from('resource');
        $this->fetchAll();
    }

    function it_can_limit_maximum_results(Fetcher $fetcher)
    {
        $fetcher->fetchAll('SELECT * FROM resource LIMIT 42 OFFSET 2', array())->shouldBeCalled();

        $this->limit(42);
        $this->offset(2);
        $this->from('resource');
        $this->fetchAll();
    }
}
