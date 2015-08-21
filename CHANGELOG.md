# Changes between versions

## 0.2.1: Pomm implementation

* added link to [PommSearchEngine](https://github.com/gnugat/pomm-search-engine), a [Pomm Foundation](http://www.pomm-project.org/) implementation

## 0.2.0: array results

Previously, results were returned as encoded JSON.

## 0.1.0: Proof Of Concept

* IdentifierEngine can:
    * embed relations
    * filter string with a case insensitive regex (for Postgres only)
    * filter ID and booleans by equality
    * filter ranges (plural name, comma separated list: `author_ids=1,2,3`)
* SearchEngine can do the same things, plus:
    * order many fields, with direction (prefix with `-` for DESCendant ordering)
    * paginate results with total information
* Fetcher interface allows integration with "database library" of choice (e.g. PDO, Pomm, Doctrine, etc)
* FilteringBuilderStrategy interface allows vendor specific or domain specific filtering
