<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;

use Doctrine\Common\Inflector\Inflector;
use Gnugat\SearchEngine\Builder\FilteringBuilderStrategy;
use Gnugat\SearchEngine\Builder\QueryBuilder;
use Gnugat\SearchEngine\ResourceDefinition;
use Gnugat\SearchEngine\Service\TypeSanitizer;

class RangeFilteringBuilderStrategy implements FilteringBuilderStrategy
{
    /**
     * @var TypeSanitizer
     */
    private $typeSanitizer;

    /**
     * @param TypeSanitizer $typeSanitizer
     */
    public function __construct(TypeSanitizer $typeSanitizer)
    {
        $this->typeSanitizer = $typeSanitizer;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ResourceDefinition $resourceDefinition, $field, $value)
    {
        return false === $resourceDefinition->hasField($field) && true === $resourceDefinition->hasField(Inflector::singularize($field));
    }

    /**
     * {@inheritdoc}
     */
    public function build(QueryBuilder $queryBuilder, ResourceDefinition $resourceDefinition, $field, $value)
    {
        $value = trim($value, ',');
        if (empty($value)) {
            return;
        }
        $field = Inflector::singularize($field);
        $type = $resourceDefinition->getFieldType($field);
        $parameterNames = array();
        foreach (explode(',', $value) as $parameterNumber => $parameterValue) {
            $parameterName = ':'.$field.'_'.$parameterNumber;
            $sanitizedParameterValue = $this->typeSanitizer->sanitize($parameterValue, $type);
            $queryBuilder->addParameter($parameterName, $sanitizedParameterValue, $type);
            $parameterNames[] = $parameterName;
        }
        $queryBuilder->addWhere($field.' IN ('.implode(', ', $parameterNames).')');
    }
}
