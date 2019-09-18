<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ElasticaSortOrderMapper
{
    /**
     * Maps sort to query.
     */
    public function mapSortOrder(object $sortOrder, Query $query): void;
}
