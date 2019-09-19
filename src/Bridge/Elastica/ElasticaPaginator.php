<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;
use Elastica\ResultSet;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ElasticaPaginator
{
    public function sliceQuery(object $pagination, Query $query): void;

    public function getResultsCount(ResultSet $resultSet): int;
}
