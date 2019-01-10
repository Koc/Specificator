<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface PaginationMapper
{
    public function __invoke(object $pagination, QueryBuilder $queryBuilder): void;
}
