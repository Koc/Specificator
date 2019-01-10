<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface PaginationMapper
{
    public function __invoke(object $pagination, Query $query): void;
}
