<?php

namespace Brouzie\Specificator\Bridge\Elastica\ResultBuilder;

use Elastica\Query;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface QueryStageResultBuilder
{
    public function __invoke(Query $query): void;
}
