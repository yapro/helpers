<?php

declare(strict_types=1);

namespace YaPro\Helper\CommonTrait;

trait PaginationTrait
{
    const FIRST_PAGE_NUMBER = 1;
    private int $currentPageNumber = 0;
    private int $offset;
    private int $limit;

    private function initPaginationFields(int $page, int $itemsPerPage = 10): void
    {
        // client может указать номер страницы, но страница не должна быть меньше нуля
        $this->currentPageNumber = $page > self::FIRST_PAGE_NUMBER ? $page : self::FIRST_PAGE_NUMBER;

        // client может сам указать кол-во возвращаемых строк, но не более 100
        $itemsPerPage = $itemsPerPage < 100 ? $itemsPerPage : 100;

        $this->offset = ($this->currentPageNumber - 1) * $itemsPerPage;
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

    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    public function isFirstPageNow(): bool
    {
        return $this->currentPageNumber === self::FIRST_PAGE_NUMBER;
    }
}
