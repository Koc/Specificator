<?php

namespace Brouzie\Specificator\Locator;

use Brouzie\Specificator\Exception\MapperNotFound;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Psr11FilterMappingLocator implements FilterMapperLocator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    //TODO: register like message handlers https://github.com/symfony/symfony/blob/df4ad4e7d4997a2f186c14312a250f2fca2c1b03/src/Symfony/Component/Messenger/DependencyInjection/MessengerPass.php#L135
    public function getFilterMapper(string $filter): callable
    {
        try {
            return $this->container->get($filter);
        } catch (NotFoundExceptionInterface $e) {
            throw new MapperNotFound(sprintf('No mapper for filter "%s".', $filter), 0, $e);
        }
    }
}
