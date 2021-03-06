<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class FilterSubscription
{
    private $filterClass;
    private $mapperMethod;

    public function __construct(string $filterClass, string $mapperMethod)
    {
        $this->filterClass = $filterClass;
        $this->mapperMethod = $mapperMethod;
    }

    public function getFilterClass(): string
    {
        return $this->filterClass;
    }

    public function getMapperMethod(): string
    {
        return $this->mapperMethod;
    }
}
