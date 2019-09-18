<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface DoctrineOrmFilterMapper
{
    public function mapFilter(object $filter, QueryBuilder $queryBuilder): void;
}
