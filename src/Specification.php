<?php

namespace Brouzie\Specificator;

use Brouzie\Specificator\Pagination\LimitOffsetPagination;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class Specification
{
    private $filtersBag;
    private $sortOrdersBag;
    private $pagination;

    /**
     * @param object[] $filters
     * @param object[] $sortOrders
     * @param object|null $pagination
     */
    public function __construct(array $filters = [], array $sortOrders = [], object $pagination = null)
    {
        $this->filtersBag = new \SplObjectStorage();
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }

        $this->sortOrdersBag = new \SplObjectStorage();
        foreach ($sortOrders as $sortOrder) {
            $this->addSortOrder($sortOrder);
        }

        $this->setPagination($pagination ?: LimitOffsetPagination::fromPage(20, 1));
    }

    public function addFilter(object $filter): void
    {
        $this->filtersBag->attach($filter);
    }

    public function addAggregation(string $name, object $aggregation): void
    {
        // ...
    }

    /**
     * @return object[]
     */
    public function getFilters(): array
    {
        return iterator_to_array($this->filtersBag);
    }

    public function addSortOrder(object $sortOrder): void
    {
        $this->sortOrdersBag->attach($sortOrder);
    }

    /**
     * @return object[]
     */
    public function getSortOrders(): array
    {
        return iterator_to_array($this->sortOrdersBag);
    }

    public function setPagination(object $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): object
    {
        return $this->pagination;
    }
}
