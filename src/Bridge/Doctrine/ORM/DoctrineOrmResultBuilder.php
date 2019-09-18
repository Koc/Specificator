<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface DoctrineOrmResultBuilder
{
    public function modifyQuery(QueryBuilder $queryBuilder): void;

    public function hydrateItems(iterable $result): iterable;
}
