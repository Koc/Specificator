<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Brouzie\Specificator\Specification;
use Elastica\Query;
use Elastica\ResultSet;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ElasticaResultBuilder
{
    public function modifyQuery(Query $query, Specification $specification): void;

    public function hydrateItems(ResultSet $resultSet): iterable;
}
