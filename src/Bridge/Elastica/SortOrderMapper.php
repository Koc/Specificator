<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SortOrderMapper
{
    /**
     * Maps sort to query.
     */
    public function __invoke(object $sortOrder, Query $query): void;
}
