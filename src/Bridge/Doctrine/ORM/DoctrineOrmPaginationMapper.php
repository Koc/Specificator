<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface DoctrineOrmPaginationMapper
{
    public function sliceQuery(object $pagination, QueryBuilder $queryBuilder): void;
}
