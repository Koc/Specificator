<?php

namespace Brouzie\Specificator\Http;

use Brouzie\Specificator\Specification;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SpecificationBuilder
{
    public function buildSpecification(Specification $specification, object $request): void;
}
