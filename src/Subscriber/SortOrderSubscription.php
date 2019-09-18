<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class SortOrderSubscription
{
    private $sortOrderClass;
    private $mapperMethod;

    public function __construct(string $sortOrderClass, string $mapperMethod)
    {
        $this->sortOrderClass = $sortOrderClass;
        $this->mapperMethod = $mapperMethod;
    }

    public function getSortOrderClass(): string
    {
        return $this->sortOrderClass;
    }

    public function getMapperMethod(): string
    {
        return $this->mapperMethod;
    }
}
