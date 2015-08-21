<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Test\Fixtures\Project;

use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Test\Fixtures\PdoSearchEngine\PdoFetcher;
use PDO;
use Symfony\Component\Yaml\Yaml;

class ProjectBuild
{
    /**
     * @return ResourceDefinition
     */
    public static function blogResourceDefinition()
    {
        return new ResourceDefinition(
            'blog',
            array(
                'id' => ResourceDefinition::TYPE_INTEGER,
                'title' => ResourceDefinition::TYPE_STRING,
                'author_id' => ResourceDefinition::TYPE_INTEGER,
            ),
            array('author')
        );
    }

    /**
     * @return ResourceDefinition
     */
    public static function blogSelectBuilder()
    {
        return new BlogSelectBuilder();
    }

    /**
     * @return PdoFetcher
     */
    public static function fetcher()
    {
        return new PdoFetcher(self::pdo());
    }

    /**
     * @return PDO
     */
    public static function pdo()
    {
        $config = self::config();

        return new PDO(
            'pgsql:host='.$config['host'].' port='.$config['port'].' dbname='.$config['database'],
            $config['username'],
            $config['password']
        );
    }

    /**
     * @return array
     */
    public static function config()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/config.yml'));

        return $config['parameters'];
    }
}
