<?php

namespace Brouzie\Specificator;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Result
{
    private $items;

    private $totalItemsCount;

    /**
     * @param object $items
     * @param int $totalItemsCount
     */
    public function __construct(array $items, int $totalItemsCount)
    {
        $this->items = $items;
        $this->totalItemsCount = $totalItemsCount;
    }

    /**
     * @return object
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalItemsCount(): int
    {
        return $this->totalItemsCount;
    }
}
