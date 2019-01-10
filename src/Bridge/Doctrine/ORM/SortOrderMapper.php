<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SortOrderMapper
{
    public function __invoke(object $sortOrder, QueryBuilder $queryBuilder): void;
}
