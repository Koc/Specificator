<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ResultBuilder
{
    /**
     * @param array|object $result
     */
    public function __invoke($result): object;
}
