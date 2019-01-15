<?php

namespace Brouzie\Specificator\Bridge\Elastica\Paginator;

use Elastica\Query;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface QueryStageSlicer
{
    /**
     * Apply slice to query, for example:
     *
     *     public function sliceLimitOffsetQuery(LimitOffsetPagination $pagination, Query $query): void
     *     {
     *         $query
     *             ->setFrom($pagination->getOffset())
     *             ->setSize($pagination->getLimit());
     *     }
     */
    public function __invoke(object $pagination, Query $query): void;
}
