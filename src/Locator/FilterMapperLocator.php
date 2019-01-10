<?php

namespace Brouzie\Specificator\Locator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface FilterMapperLocator
{
    // register using https://github.com/symfony/symfony/blob/df4ad4e7d4997a2f186c14312a250f2fca2c1b03/src/Symfony/Component/Messenger/DependencyInjection/MessengerPass.php#L135
    public function getFilterMapper(object $filter): callable;
}
