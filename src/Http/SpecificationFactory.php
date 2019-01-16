<?php

namespace Brouzie\Specificator\Http;

use Brouzie\Specificator\Specification;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class SpecificationFactory
{
    private $specificationBuilders;

    /**
     * @param iterable|SpecificationBuilder[] $specificationBuilders
     */
    public function __construct(iterable $specificationBuilders)
    {
        $this->specificationBuilders = $specificationBuilders;
    }

    public function createSpecification(object $request): Specification
    {
        $specification = new Specification();

        foreach ($this->specificationBuilders as $specificationBuilder) {
            $specificationBuilder($specification, $request);
        }

        return $specification;
    }
}
