<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Pagination\Paginator;

trait Paginate
{
    public function paginate($items)
    {
        if($items instanceof Paginator){
            return  [
                'first_page' => 1,
                'current_page' => $items->currentPage(),
                'next_page' => $items->currentPage() < $items->lastPage()? $items->currentPage()+1: null,
                'prev_page' => $items->currentPage() > 1 ? $items->currentPage()-1: null,
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total_item' => $items->total(),
            ];
        }
        return 'Items must be a type of paginator class';
    }
}
