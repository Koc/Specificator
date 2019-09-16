<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;
use Elastica\ResultSet;

interface ResultBuilder
{
    public function modifyQuery(Query $query): void;

    public function hydrateItems(ResultSet $resultSet): iterable;
}
