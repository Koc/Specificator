<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Result
{
    private $items;
    private $itemsCount;
    private $aggregations;

    /**
     * @param object[] $items
     * @param int $itemsCount
     * @param object[] $aggregations
     */
    public function __construct(array $items, int $itemsCount, array $aggregations)
    {
        $this->items = $items;
        $this->itemsCount = $itemsCount;
        $this->aggregations = $aggregations;
    }

    /**
     * @return object[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsCount(): int
    {
        return $this->itemsCount;
    }

    public function getAggregation(string $name): object
    {
        return $this->aggregations[$name];
    }
}
