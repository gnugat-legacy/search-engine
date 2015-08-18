# Search Engine

A Proof Of Concept demonstrating how to handle Interrogatory Messages.

The [Command / Query Responsibility Segregation](http://martinfowler.com/bliki/CQRS.html)
principle explains that Imperative and Interrogatory messages shouldn't be mixed together.

> **Note**: Learn more about the different flavours of messages.

Usually imperative messages are handled using the CommandBus pattern, which leaves
us with the following question: how Interrogatory Messages should be handled?

This component tries to explore one of the possible answers: a SearchEngine that
would try to return results matching a given criteria.

> **Caution**: this component does not provide actual SearchEngine features,
> if you're looking for an actual SearchEngine you should rather have a look
> at ElasticSearch or Solr.

## Installation

Download SearchEngine using [Composer](https://getcomposer.org/download):

    composer require gnugat/search:^0.1

You'll also need to choose one of the following implementation:

* none yet :( (possible implementations: Pomm Foundation, Doctrine DBAL, Doctrine ORM, etc)

Once ready, you can set it up this way:

```php
<?php

require __DIR__.'/vendor/autoload.php';

$queryBuilderFactory = new \Gnugat\PdoSearchEngine\PdoQueryBuilderFactory(); // a QueryBuilderFactory implementation of your choice
$searchEngine = \Gnugat\SearchEngine\Build::searchEngine($queryBuilderFactory);
```

> **Note**: The PDO implementation provided out of the box is only meant for demonstration purpose.
> For production, you should rely on one of the implementation listed above.

## Usage

`SearchEngine` expects a `Criteria` object which describes:

* the resource to query
* relations to embed
* filters to apply
* pagination parameters
* ordering instructions

It can be built from query parameters as follow:

```php
// ...
$criteriaFactory = new \Gnugat\SearchEngine\Build::criteriaFactory();

$criteria = $criteriaFactory->fromQueryParameters('fruit', array(
    // Filters
    'name' => 'AN',
    'farm_ids' => '1,2,3',

    // Pagination
    'page' => '2',
    'per_page' => '3',

    // Ordering
    'sort' => 'name,-farm_id',

    // Relation embeding
    'embed' => 'farm',
));
print_r($searchEngine->match($criteria));
```

In a web context, this `$queryParameters` array could actually be `$_GET`, corresponding to the following URL:

    /v1/fruits?name=AN&farm_ids=1,2,3&page=2&per_page=3&sort=name,-farm_id&embed=farm

The result could be the following:

```
array(
    'items' => array(
        array(
            'name' => 'banana',
            'farm' => array(
                'id' => 2,
            ),
        ),
        array(
            'name' => 'banana',
            'farm' => array(
                'id' => 1,
            ),
        )
    ),
    'page' => array(
        'current_page' => 2,
        'per_page' => 3,
        'total_elements' => 5,
        'total_pages' => 2,
    ),
)
```

## Features

* embeding relations
* filtering string with a case insensitive regex
* filtering ID and booleans by equality
* filtering ranges (plural name, comma separated list: `farm_ids=1,2,3`)
* ordering many fields, with direction (prefix with `-` for DESCendant ordering)
* paginating results with total information
