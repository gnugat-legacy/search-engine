# Search Engine

A Proof Of Concept demonstrating how to handle Interrogatory Messages.

The [Command / Query Responsibility Segregation](http://martinfowler.com/bliki/CQRS.html)
principle explains that Imperative and Interrogatory messages shouldn't be mixed together.

> **Note**: Learn more about the different [messaging flavours](http://verraes.net/2015/01/messaging-flavours/).

Usually imperative messages are handled using the CommandBus pattern, which leaves
us with the following question: how Interrogatory Messages should be handled?

This component tries to explore one of the possible answers: a SearchEngine that
would try to return results matching a given criteria.

> **Caution**: this component does not provide actual SearchEngine features,
> if you're looking for one you should rather have a look at ElasticSearch, Solr, etc.

## Installation

Download SearchEngine using [Composer](https://getcomposer.org/download):

    composer require gnugat/search-engine:^0.1

You'll also need to choose one of the following implementations:

* none yet :( (possible implementations: PDO, Pomm Foundation, Doctrine DBAL, Doctrine ORM, etc)

> **Note**: For more information about how to instantiate the classes, see [the installation documentation](doc/01-installation.md).

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
$criteria = $criteriaFactory->fromQueryParameters('blog', array(
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
));
print_r($searchEngine->match($criteria));
```

In a web context, this `$queryParameters` array could actually be `$_GET`, corresponding to the following URL:

    /v1/blogs?title=IG&author_ids=1,2&page=1&per_page=3&sort=author_id,-title&embed=author

The result could be the following:

```
array(
    'items' => array(
        array(
            'id' => 1,
            'title' => 'Big Title',
            'author_id' => 1,
        ),
        array(
            'id' => 2,
            'title' => 'Big Header',
            'author_id' => 2,
        ),
    ),
    'page' => array(
        'current_page' => 1,
        'per_page' => 3,
        'total_elements' => 2,
        'total_pages' => 1,
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

* [Installation](doc/01-installtion.md)
* [Extending](doc/02-extending.md)
