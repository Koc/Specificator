<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query\BoolQuery;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ElasticaFilterMapper
{
    /**
     * Maps filter to query.
     */
    public function mapFilter(object $filter, BoolQuery $query): void;
}
