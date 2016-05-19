# Implementing

`SearchEngine` receives a `Criteria` which describes how the data should be
retrieved.

For example it could ask for all `profiles`:

```php
$criteria = Criteria::fromQueryParameters('profile', [
    // Nothing here means "find me everything you can"
]);
```

It could also ask for all purchases for the given `profile`:

```php
$criteria = Criteria::fromQueryParameters('purchasing', [
    // `profile_id` is a field we'd like to use as a filter
    'profile_id' => '66f856fb-186a-4f2a-8f79-8351ad0fea70',
]);
```

`SearchEngine` returns a `Porpaginas\Result`, allowing us to choose between getting
**all** results or a subset of it (paginating):

```php
$result = $searchEngine->match($criteria);

$allResults = iterator_to_array($result->getIterator());

$paginatedResults = iterator_to_array($result->take(
    $criteria->paginating->offset
    $criteria->paginating->itemsPerPage
)->getIterator());
```

Selecting, Filtering, Ordering and Embeding logic cannot be written directly in
`SearchEngine` implementations, otherwise for each new resource or filter we'd
need to cram more code in them.

So `SearchEngine` implementations are likely to follow the [Strategy design pattern](https://en.wikipedia.org/wiki/Strategy_pattern):

* "Strategies" can be registered in it
* when receiving `Criteria`, `SearchEngine` will call "Strategies" that support it

## Array implementation example

The `tests` directory provides an example of an `Array` implementation, let's go
through it:

```php
<?php

namespace Gnugat\SearchEngine\Test\ArrayImplementation;

use Porpaginas\Arrays\ArrayResult;
use Porpaginas\Result;
use Gnugat\SearchEngine\{Criteria, SearchEngine};

class ArraySearchEngine implements SearchEngine
{
    private $builders = [];
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function add(Builder $builder, int $priority = 0)
    {
        $this->builders[$priority][] = $builder;
    }

    public function match(Criteria $criteria) : Result
    {
        $data = $this->data;
        foreach ($this->builders as $priority => $builders) {
            foreach ($builders as $builder) {
                if (true === $builder->supports($criteria)) {
                    $data = $builder->build($criteria, $data);
                }
            }
        }

        return new ArrayResult($data);
    }
}
```

As we can see it doesn't do any logic with `Criteria`, it entirely relies on
`Buidlers`:

```php
<?php

namespace Gnugat\SearchEngine\Test\ArrayImplementation;

use Gnugat\SearchEngine\Criteria;

interface Builder
{
    public function supports(Criteria $criteria) : bool;
    public function build(Criteria $criteria, array $data) : array;
}
```

`Builder` is our "Strategy", with this the Array implementation is complete:
filtering a resource by a field or ordering them by IDs in descending order is
project specific so developers need to create their own `Builder` implementations.

For example they could write a `Builder` that filters "profiles" by "name":

```php
<?php

namespace Gnugat\SearchEngine\Test\ArrayBuilder;

use Gnugat\SearchEngine\Test\ArrayImplementation\Builder;
use Gnugat\SearchEngine\Criteria;

class ProfileNameFilteringBuilder implements Builder
{
    public function supports(Criteria $criteria) : bool
    {
        return 'profile' === $criteria->resourceName && isset($criteria->filtering->fields['name']);
    }

    public function build(Criteria $criteria, array $data) : array
    {
        $name = $criteria->filtering->fields['name'];

        return array_values(array_filter($data, function ($profile) use ($name) {
            return $name === $profile['name'];
        }));
    }
}
```
