<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface DoctrineOrmSortOrderMapper
{
    public function mapSortOrder(object $sortOrder, QueryBuilder $queryBuilder): void;
}
