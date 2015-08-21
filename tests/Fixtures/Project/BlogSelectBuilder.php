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

use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\Builder\SelectBuilder;
use Gnugat\SearchEngine\Criteria;
use Gnugat\SearchEngine\ResourceDefinition;

class BlogSelectBuilder implements SelectBuilder
{
    /**
     * {@inheritdoc}
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, Criteria $criteria)
    {
        $relationsToEmbed = $criteria->getEmbeding()->getRelations();
        $fields = $resourceDefinition->getFields();
        if (true === in_array('author', $relationsToEmbed, true)) {
            $authorIndex = array_search('author_id', $fields);
            $fields[$authorIndex] = <<<SQL
(
    SELECT row_to_json(d)
    FROM (
        SELECT id, name
        FROM author
        WHERE author_id = author.id
    ) d
) AS author
SQL
            ;
        }
        foreach ($fields as $field) {
            $queryBuilder->addSelect($field);
        }
        $queryBuilder->from($resourceDefinition->getName());
    }
}
