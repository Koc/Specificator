<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Result;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ResultBuilder
{
    public function __invoke(Result $result): object;
}
