<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{

    function all();

    function find(int|string $id);

    function create(array $data);
    function update($id, array $data);
    function delete(int|string $id);
    function query();
    public function findByUserAndProduct(int $userId, int $productId);
}
