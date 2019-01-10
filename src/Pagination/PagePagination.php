<?php

namespace Brouzie\Specificator\Pagination;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
final class PagePagination
{
    private $limit;

    private $page;

    public function __construct(int $limit, int $page)
    {
        $this->limit = $limit;
        $this->page = $page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
