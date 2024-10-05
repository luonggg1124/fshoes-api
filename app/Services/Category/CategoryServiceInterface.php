<?php

namespace App\Services\Category;


interface CategoryServiceInterface
{
    function getAll();
    function mains();
    function findById(int|string $id);

    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);

    function delete(int|string $id);
    function forceDelete(int|string $id);
}
