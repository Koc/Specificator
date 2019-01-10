<?php

namespace Brouzie\Specificator\Locator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface PaginationMapperLocator
{
    public function getPaginationMapper(object $pagination): callable;
}
