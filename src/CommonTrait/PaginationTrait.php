<?php

declare(strict_types=1);

namespace YaPro\Helper\CommonTrait;

trait PaginationTrait
{
    private int $offset;
    private int $limit;

    private function initPaginationFields(int $page, int $itemsPerPage): void
    {
        // client может указать номер страницы, но страница не должна быть меньше нуля
        $page = $page > 0 ? $page - 1 : 0;

        // client может сам указать кол-во возвращаемых строк, но не более 100
        $itemsPerPage = $itemsPerPage < 100 ? $itemsPerPage : 100;

        $this->offset = $page * $itemsPerPage;
        $this->limit = $itemsPerPage;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
