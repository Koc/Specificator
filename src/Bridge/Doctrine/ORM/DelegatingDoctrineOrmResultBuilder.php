<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class DelegatingDoctrineOrmResultBuilder implements DoctrineOrmResultBuilder
{
    public function modifyQuery(QueryBuilder $queryBuilder): void
    {
        // TODO: Implement modifyQuery() method.
    }

    public function hydrateItems(iterable $result): iterable
    {
        // TODO: Implement hydrateItems() method.
    }
}
