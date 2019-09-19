<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

interface DoctrineOrmCountablePaginationMapper
{
    public function getItemsCount(QueryBuilder $queryBuilder): int;
}
