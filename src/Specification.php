<?php

namespace Brouzie\Specificator;

use Brouzie\Specificator\Pagination\PagePagination;

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
     * @param object[] $sorts
     * @param object|null $pagination
     */
    public function __construct(array $filters = [], array $sorts = [], object $pagination = null)
    {
        $this->filtersBag = new \SplObjectStorage();
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }

        $this->sortOrdersBag = new \SplObjectStorage();
        foreach ($sorts as $sort) {
            $this->addSortOrder($sort);
        }

        $this->setPagination($pagination ?: new PagePagination(20, 1));
    }

    public function addFilter(object $filter): void
    {
        $this->filtersBag->attach($filter);
    }

    /**
     * @return object[]
     */
    public function getFilters(): array
    {
        //TODO: implement
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
        //TODO: implement
    }

    public function setPagination(object $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): object
    {
        //TODO: implement
    }
}
