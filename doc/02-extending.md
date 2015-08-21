# Extending

SearchEngine can be extended in many ways:

* [Fetcher](#fetcher)
* [FilteringBuilderStrategy](#filteringbuilderstrategy)

## Fetcher

With a bit of luck, your favorite "database library" would have been integrated
with SearchEngine already. If it's not the case, don't panic! It's as simple as
implementing `Fetcher`.

Here's an example (using fixtures that can be found in `tests/Fixtures/PdoSearchEngine`):

```php
<?php

use Gnugat\SearchEngine\Fetcher;
use Gnugat\SearchEngine\ResourceDefinition;
use PDO;

class PdoFetcher implements Fetcher
{
    private $pdo;
    private $types;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->types = array(
            ResourceDefinition::TYPE_INTEGER => PDO::PARAM_INT,
            ResourceDefinition::TYPE_NULL => PDO::PARAM_NULL,
            ResourceDefinition::TYPE_STRING => PDO::PARAM_STR,
        );
    }

    public function fetchAll($sql, array $parameters)
    {
        $wrappedSql = 'SELECT array_to_json(array_agg(row_to_json(t))) FROM ('.$sql.') t';
        $result = $this->getPDOStatement($wrappedSql, $parameters)->fetch(PDO::FETCH_ASSOC);

        return (true === isset($result['array_to_json']) && null !== $result['array_to_json']) ? $result['array_to_json'] : '[]';
    }

    public function fetchFirst($sql, array $parameters)
    {
        $wrappedSql = 'SELECT row_to_json(t) FROM ('.$sql.') t';
        $result = $this->getPDOStatement($wrappedSql, $parameters)->fetch(PDO::FETCH_ASSOC);

        return (true === isset($result['row_to_json']) && null !== $result['row_to_json']) ? $result['row_to_json'] : null;
    }

    private function getPDOStatement($sql, array $parameters)
    {
        $pdoStatement = $this->pdo->prepare($sql);
        foreach ($parameters as $parameter) {
            $pdoStatement->bindParam($parameter['name'], $parameter['value'], $this->types[$parameter['type']]);
        }
        $pdoStatement->execute();

        return $pdoStatement;
    }
}
```

Possible implementation could be: PDO, Pomm Foundation, Doctrine DBAL, Doctrine ORM, etc.

## FilteringBuilderStrategy

In order to filter resulsts, SearchEngine relies on implementations of `FilteringBuilderStrategy`.

Out of the box it provides the following vendor agnostic implementations:

* `RangeFilteringBuilderStrategy`, allows you to do a `WHERE field IN (values)` (priority 30)
* `StrictComparisonFilteringBuilderStrategy`, allows you to do a `WHERE field = value` (priority 10)

Those are already registered in `SearchEngine` and `IndentifierEngine` if you use `Build`.

It also provides one Postgres specific implementation:

* `RegexFilteringBuilderStrategy`, allows you to do a `WHERE field ~* .*value.*`

This one needs to be registered as follow:

```php
use Gnugat\SearchEngine\Build;
use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy\RegexFilteringBuilderStrategy;

Build::addFilteringBuilderStrategy(new RegexFilteringBuilderStrategy(), 20);
```

> **Note**: The second argument is a priority index.
> With a high number, you force SearchEngine to check your `FilteringBuilderStrategy` before the others.

You could also create a domain specific filter:

```php
<?php

use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;

class CanOnlySeeTheirOwnFilteringBuilderStrategy implements FilteringBuilderStrategy
{
    public function supports(ResourceDefinition $resourceDefinition, $field, $value)
    {
        return $resourceDefinition->getName('blog') && $field === 'current_user_id';
    }

    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value)
    {
        $queryBuilder->addWhere('author_id = :current_user_id');
        $queryBuilder->addParameter(':current_user_id', $value, ResourceDefinition::TYPE_INTEGER);
    }
}
```
