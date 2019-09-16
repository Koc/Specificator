<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Exception\MapperNotFound;
use Doctrine\ORM\QueryBuilder;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DelegatingFilterMapper implements FilterMapper
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function mapFilter(object $filter, QueryBuilder $queryBuilder): void
    {
        $filterMapper = $this->getFilterMapper($filter);
        $filterMapper($filter, $queryBuilder);
    }

    //TODO: register like message handlers https://github.com/symfony/symfony/blob/df4ad4e7d4997a2f186c14312a250f2fca2c1b03/src/Symfony/Component/Messenger/DependencyInjection/MessengerPass.php#L135
    private function getFilterMapper(string $filter): callable
    {
        try {
            return $this->container->get($filter);
        } catch (NotFoundExceptionInterface $e) {
            throw new MapperNotFound(sprintf('No mapper for filter "%s".', $filter), 0, $e);
        }
    }
}
