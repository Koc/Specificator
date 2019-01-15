<?php

namespace Brouzie\Specificator\Bridge\Elastica\Paginator;

use Elastica\ResultSet;

interface ResultStageCounter
{
    public function __invoke(ResultSet $resultSet): int;
}
