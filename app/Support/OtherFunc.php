<?php

namespace App\Support;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OtherFunc
{
    public static function paginate($items, $currentPage = null, $perPage = null, array $options = [])
    {
        $currentPage = $currentPage ? : 1;
        $perPage = $perPage ? : 10;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $results = new LengthAwarePaginator($items->forPage($currentPage, $perPage), $items->count(), $perPage, $currentPage, $options);
        return $results->values();

    }
}