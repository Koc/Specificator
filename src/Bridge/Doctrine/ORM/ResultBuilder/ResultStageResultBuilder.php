<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM\ResultBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface ResultStageResultBuilder
{
    /**
     * @param array[]|object[] $result
     *
     * @return object[]
     */
    public function __invoke($result): array;
}
