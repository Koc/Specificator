<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SpecificationExecutor
{
    public function execute(Specification $specification, string $resultItemClass): Result;

    //TODO: add extra interface for specifications
}
