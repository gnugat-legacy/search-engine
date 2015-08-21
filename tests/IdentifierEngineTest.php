<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test;

use Gnugat\SearchEngine\Build;
use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy\RegexFilteringBuilderStrategy;
use Gnugat\SearchEngine\Test\Fixtures\Project\ProjectBuild;
use PHPUnit_Framework_TestCase;

class IdentifierEngineTest extends PHPUnit_Framework_TestCase
{
    private $criteriaFactory;
    private $identifierEngine;

    protected function setUp()
    {
        $fetcher = ProjectBuild::fetcher();
        $blogResourceDefinition = ProjectBuild::blogResourceDefinition();
        $blogSelectBuilder = ProjectBuild::blogSelectBuilder();

        Build::addFilteringBuilderStrategy(new RegexFilteringBuilderStrategy(), 20);
        $this->criteriaFactory = Build::criteriaFactory();
        $this->identifierEngine = Build::identifierEngine($fetcher);
        $this->identifierEngine->add('blog', $blogResourceDefinition, $blogSelectBuilder);
    }

    /**
     * @test
     */
    public function it_finds_first_result()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('id' => '1'));
        $results = $this->identifierEngine->match($criteria);

        self::assertSame(array(
            'id' => 1,
            'title' => 'Big Title',
            'author_id' => 1,
        ), json_decode($results, true));
    }

    /**
     * @test
     *
     * @expectedException \Gnugat\SearchEngine\NoMatchException
     */
    public function it_fails_when_nothing_match()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('id' => '0'));
        $this->identifierEngine->match($criteria);
    }

    /**
     * @test
     */
    public function it_can_whitelist()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('author_ids' => '1,3'));
        $results = $this->identifierEngine->match($criteria);

        self::assertSame(array(
            'id' => 1,
            'title' => 'Big Title',
            'author_id' => 1,
        ), json_decode($results, true));
    }

    /**
     * @test
     */
    public function it_can_filter_using_case_insensitive_regex()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('title' => 'IG'));
        $results = $this->identifierEngine->match($criteria);

        self::assertSame(array(
            'id' => 1,
            'title' => 'Big Title',
            'author_id' => 1,
        ), json_decode($results, true));
    }

    /**
     * @test
     */
    public function it_can_embed_relations()
    {
        $criteria = $this->criteriaFactory->fromQueryParameters('blog', array('id' => '1', 'embed' => 'author'));
        $results = $this->identifierEngine->match($criteria);

        self::assertSame(array(
            'id' => 1,
            'title' => 'Big Title',
            'author' => array(
                'id' => 1,
                'name' => 'Nate',
            ),
        ), json_decode($results, true));
    }
}
