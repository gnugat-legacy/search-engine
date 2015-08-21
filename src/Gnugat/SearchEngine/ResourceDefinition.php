<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine;

class ResourceDefinition
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_NULL = 'null';
    const TYPE_STRING = 'string';

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $typedFields;

    /**
     * @var array
     */
    private $relations;

    /**
     * @param string $name
     * @param array  $typedFields
     * @param array  $relations
     */
    public function __construct($name, array $typedFields, array $relations = array())
    {
        $this->name = $name;
        $this->typedFields = $typedFields;
        $this->relations = $relations;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFields()
    {
        return array_keys($this->typedFields);
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasField($field)
    {
        return isset($this->typedFields[$field]);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getFieldType($field)
    {
        return $this->typedFields[$field];
    }

    /**
     * @return string
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param string $relation
     *
     * @return bool
     */
    public function hasRelation($relation)
    {
        return in_array($relation, $this->relations, true);
    }
}
