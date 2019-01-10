<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface QueryRepository
{
    public function query(Specification $specification, string $resultItemClass): Result;
}
