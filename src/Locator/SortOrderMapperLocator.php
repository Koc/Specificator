<?php

namespace Brouzie\Specificator\Locator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SortOrderMapperLocator
{
    public function getSortOrderMapper(object $sortOrder): callable;
}
