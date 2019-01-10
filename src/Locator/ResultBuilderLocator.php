<?php

namespace Brouzie\Specificator\Locator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ResultBuilderLocator
{
    public function getResultBuilder(string $resultClass): callable;
}
