<?php

namespace App\Repositories\Category;

interface CategoryRepositoryInterface
{
    function all();
    function find($id);
}
