<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM\ResultBuilder;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface QueryStageResultBuilder
{
    public function __invoke(QueryBuilder $queryBuilder): void;
}
