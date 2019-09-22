<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface DoctrineOrmCountablePaginationMapper
{
    public function getItemsCount(QueryBuilder $queryBuilder): int;
}
