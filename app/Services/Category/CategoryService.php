<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepositoryInterface;

class CategoryService
{

    public function __construct(protected CategoryRepositoryInterface $categoryRepository){}

    public function getAll(){
        return $this->categoryRepository->all();
    }

    public function getById(int $id){
        return $this->categoryRepository->find($id);
    }
}
