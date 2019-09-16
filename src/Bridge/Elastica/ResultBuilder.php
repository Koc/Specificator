<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;
use Elastica\ResultSet;

interface ResultBuilder
{
    public function applyQueryStage(Query $query): void;

    public function hydrateItems(ResultSet $resultSet): iterable;
}
