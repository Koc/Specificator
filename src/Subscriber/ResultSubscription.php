<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class ResultSubscription
{
    private $itemClass;
    private $queryMethod;
    private $hydrationMethod;

    public function __construct(string $itemClass, ?string $queryMethod, string $hydrationMethod)
    {
        $this->itemClass = $itemClass;
        $this->queryMethod = $queryMethod;
        $this->hydrationMethod = $hydrationMethod;
    }

    public function getItemClass(): string
    {
        return $this->itemClass;
    }

    public function getQueryMethod(): ?string
    {
        return $this->queryMethod;
    }

    public function getHydrationMethod(): string
    {
        return $this->hydrationMethod;
    }
}
