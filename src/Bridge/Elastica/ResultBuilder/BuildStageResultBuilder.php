<?php

namespace Brouzie\Specificator\Bridge\Elastica\ResultBuilder;

use Elastica\Result;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface BuildStageResultBuilder
{
    /**
     * @return object[]
     */
    public function __invoke(Result $result): array;
}
