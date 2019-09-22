<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface QueryRepository
{
    /**
     * @return string[]
     */
    public static function getMappersClasses(): array;
}
