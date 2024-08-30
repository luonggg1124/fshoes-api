<?php

namespace App\Repositories\Category;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }
    public function find($id){
        return Category::query()->findOrFail($id);
    }
}
