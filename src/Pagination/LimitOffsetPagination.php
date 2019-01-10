<?php

namespace Brouzie\Specificator\Pagination;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
final class LimitOffsetPagination
{
    private $limit;

    private $offset;

    public function __construct(int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
