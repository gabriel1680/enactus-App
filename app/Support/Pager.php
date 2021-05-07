<?php

namespace App\Support;

use CoffeeCode\Paginator\Paginator;

class Pager extends Paginator
{
    public function __construct(string $link = null, string $title = null, array $first = null, array $last = null)
    {
        parent::__construct($link, $title, $first, $last);
    }

    public function setPager(int $rows, int $page = null, int $limit = CONF_PAGINATOR_LIMIT, int $range = CONF_PAGINATOR_OFFSET, string $hash = null, array $params = [])
    {
        parent::pager($rows, $limit, $page, $range, $hash, $params);
    }
}
