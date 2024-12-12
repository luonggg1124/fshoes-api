<?php

namespace App\Services\Category;


interface CategoryServiceInterface
{
    function getAll();
    function mains();
    function findById(int|string $id);
    function displayHomePage();
    function addProducts(int|string $id, array $products = []);
    function deleteProducts(int|string $id, array $products = []);
    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);

    function delete(int|string $id);
    function forceDelete(int|string $id);
}
