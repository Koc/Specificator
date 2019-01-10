<?php

namespace Brouzie\Specificator\Locator;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Psr11FilterMappingLocator implements FilterMapperLocator
{
    private $container;

    private $filterToMapperIdMap;

    public function __construct(ContainerInterface $container, array $filterToMapperIdMap)
    {
        $this->container = $container;
        $this->filterToMapperIdMap = $filterToMapperIdMap;
    }

    public function getFilterMapper(object $filter): callable
    {
        $filterClass = \get_class($filter);
        $mapperId = $this->filterToMapperIdMap[$filterClass] ?? null;

        if (null === $mapperId) {
            //TODO: create exception
            throw new MapperNotFound();
        }

        try {
            return $this->container->get($mapperId);
        } catch (NotFoundExceptionInterface $e) {
            throw new MapperNotFound($e);
        }
    }
}
