# Search Engine [![SensioLabsInsight](https://insight.sensiolabs.com/projects/0bf23baf-b278-4be1-9e20-0a12472bb0ed/mini.png)](https://insight.sensiolabs.com/projects/0bf23baf-b278-4be1-9e20-0a12472bb0ed) [![Build Status](https://travis-ci.org/gnugat/search-engine.svg?branch=master)](https://travis-ci.org/gnugat/search-engine)

A Proof Of Concept demonstrating how to handle Interrogatory Messages (Query in CQRS).

The [Command / Query Responsibility Segregation](http://martinfowler.com/bliki/CQRS.html)
principle explains that Imperative and Interrogatory messages shouldn't be mixed together.

> **Note**: Learn more about the different [messaging flavours](http://verraes.net/2015/01/messaging-flavours/).

[Usually imperative messages are handled using the CommandBus pattern](https://gnugat.github.io/2016/05/11/towards-cqrs-command-bus.html),
which leaves us with the following question: how Interrogatory Messages should
be handled?

[This component tries to explore one of the possible answers](https://gnugat.github.io/2016/05/18/towards-cqrs-search-engine.html):
a SearchEngine that would try to return results matching a given criteria.

> **Caution**: this component does not provide actual SearchEngine features,
> if you're looking for one you should rather have a look at ElasticSearch, Solr, etc.

## Installation

Download SearchEngine using [Composer](https://getcomposer.org/download):

    composer require gnugat/search-engine:^0.2

You'll also need to choose one of the following implementations:

* [PommSearchEngine](https://github.com/gnugat/pomm-search-engine), a [Pomm Foundation](http://www.pomm-project.org/) implementation
* hey you just met this library, and this is crazy, but it has interfaces, so implement them, maybe?

Other possible implementations: PDO, [Doctrine DBAL](https://gnugat.github.io/2016/05/18/towards-cqrs-search-engine.html),
Doctrine ORM, etc. In the `tests` directory you'll find an `Array` implementation example.

[More information about implementations](doc/01-implementing.md)

## Usage

`SearchEngine` expects a `Criteria` object which describes:

* the resource to query
* relations to embed
* filters to apply
* orderings instructions
* pagination parameters

It can be built from query parameters as follow:

```php
// ...
$criteria = $criteriaFactory->fromQueryParameters('blog', [
    // Filters
    'title' => 'IG',
    'author_ids' => '1,3',

    // Pagination
    'page' => '2',
    'per_page' => '3',

    // Ordering
    'sort' => 'author_id,-title',

    // Relation embeding
    'embed' => 'author',
]);
print_r(iterator_to_array(
    $searchEngine
        ->match($criteria)
        ->take(
            $criteria->paginating->offset,
            $criteria->paginating->itemsPerPage
        )
        ->getIterator()
));
```

In a web context, this `$queryParameters` array could actually be `$_GET`, corresponding to the following URL:

    /v1/blogs?title=IG&author_ids=1,2&page=1&per_page=3&sort=author_id,-title&embed=author

The result could be the following:

```
[
    'items' => [
        [
            'id' => 1,
            'title' => 'Big Title',
            'author_id' => 1,
        ],
        [
            'id' => 2,
            'title' => 'Big Header',
            'author_id' => 2,
        ],
    ],
    'page' => [
        'current_page' => 1,
        'per_page' => 3,
        'total_elements' => 2,
        'total_pages' => 1,
    ],
]
```

## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/gnugat/search-engine/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)

Next readings:

* [Implementing](doc/01-implementing.md)
