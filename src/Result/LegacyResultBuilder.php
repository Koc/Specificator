<?php

namespace Brouzie\Specificator\Result;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 * @deprecated Use specific result builder
 */
class LegacyResultBuilder
{
    private $queryStage;

    private $resultStage;

    public function __construct(?callable $queryStage, ?callable $resultStage)
    {
        $this->queryStage = $queryStage;
        $this->resultStage = $resultStage;
    }

    public function getQueryStage(): ?callable
    {
        return $this->queryStage;
    }

    public function getResultStage(): ?callable
    {
        return $this->resultStage;
    }
}
