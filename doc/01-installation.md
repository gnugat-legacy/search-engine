# Installation

Download SearchEngine using [Composer](https://getcomposer.org/download):

    composer require gnugat/search-engine:^0.2

You'll also need to choose one of the following implementations:

* [PommSearchEngine](https://github.com/gnugat/pomm-search-engine), a [Pomm Foundation](http://www.pomm-project.org/) implementation

Other possible implementations: PDO, Doctrine DBAL, Doctrine ORM, etc.

## Instantiation

SearchEngine provides a `Build` class to help you instantiate its classes:

```php
use Gnugat\SearchEngine\Build;

$criteriaFactory = Build::criteriaFactory();
$identifierEngine = Build::identifierEngine($fetcher);
$searchEngine = Build::searchEngine($fetcher);
```

> **Note**: The `$fetcher` variable should be an implementation of `Fetcher`.
> See [the extension documentation](02-extending.md) for more information about it.

In order to be able to find anything, `SearchEngine` and `IdentifierEngine` both
need you to add information about available resources:

* the resource name
* an instance of `ResourceDefinition`, to define the valid fields with their types, and the valid relations
* an implementation of `SelectBuilder`, to allow you to describe which fields to include

Here's an example (using fixtures that can be found in `tests/Fixtures/Project`):

```php
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\Builder\SelectBuilder;
use Gnugat\SearchEngine\Criteria;
use Gnugat\SearchEngine\ResourceDefinition;

class BlogSelectBuilder implements SelectBuilder
{
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

$blogSelectBuilder = new BlogSelectBuilder();
$blogResourceDefinition = new ResourceDefinition(
    'blog',
    array(
        'id' => ResourceDefinition::TYPE_INTEGER,
        'title' => ResourceDefinition::TYPE_STRING,
        'author_id' => ResourceDefinition::TYPE_INTEGER,
    ),
    array('author')
);

$searchEngine->add('blog', $blogResourceDefinition, $blogSelectBuilder);
$identifierEngine->add('blog', $blogResourceDefinition, $blogSelectBuilder);
```
