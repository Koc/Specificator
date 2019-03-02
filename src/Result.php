<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Result
{
    private $items;

    private $totalItemsCount;

    private $aggregations;

    /**
     * @param object[] $items
     * @param int $totalItemsCount
     * @param object[] $aggregations
     */
    public function __construct(array $items, int $totalItemsCount, array $aggregations)
    {
        $this->items = $items;
        $this->totalItemsCount = $totalItemsCount;
        $this->aggregations = $aggregations;
    }

    /**
     * @return object[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalItemsCount(): int
    {
        return $this->totalItemsCount;
    }

    public function getAggregation(string $name): object
    {
        return $this->aggregations[$name];
    }
}
