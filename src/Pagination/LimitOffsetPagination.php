<?php

namespace Brouzie\Specificator\Pagination;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
final class LimitOffsetPagination
{
    private $limit;
    private $offset;

    public static function fromPage(int $perPage, int $page): self
    {
        //FIXME: implement
    }

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
