<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class AggregationSubscriber
{
    private $aggregationClass;
    private $aggregationMethod;
    private $hydrationMethod;

    public function __construct(string $aggregationClass, string $aggregationMethod, string $hydrationMethod)
    {
        $this->aggregationClass = $aggregationClass;
        $this->aggregationMethod = $aggregationMethod;
        $this->hydrationMethod = $hydrationMethod;
    }

    public function getAggregationClass(): string
    {
        return $this->aggregationClass;
    }

    public function getAggregationMethod(): string
    {
        return $this->aggregationMethod;
    }

    public function getHydrationMethod(): string
    {
        return $this->hydrationMethod;
    }
}
