<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Exception\MapperNotFound;
use Doctrine\ORM\QueryBuilder;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class DelegatingDoctrineOrmSortOrderMapper implements DoctrineOrmSortOrderMapper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mapSortOrder(object $sortOrder, QueryBuilder $queryBuilder): void
    {
        $sortOrder = $this->getSortOrderMapper($sortOrder);
        $sortOrder($sortOrder, $queryBuilder);
    }

    private function getSortOrderMapper(string $sortOrder): callable
    {
        try {
            return $this->container->get($sortOrder);
        } catch (NotFoundExceptionInterface $e) {
            throw new MapperNotFound(sprintf('No mapper for sort order "%s".', $sortOrder), 0, $e);
        }
    }
}
