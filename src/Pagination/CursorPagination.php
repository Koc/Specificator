<?php

namespace Brouzie\Specificator\Pagination;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
final class CursorPagination
{
    private $limit;

    private $cursor;

    public function __construct(int $limit, string $cursor)
    {
        $this->limit = $limit;
        $this->cursor = $cursor;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getCursor(): string
    {
        return $this->cursor;
    }
}
