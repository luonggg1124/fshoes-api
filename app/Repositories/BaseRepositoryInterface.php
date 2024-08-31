<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    function all();
    function find(int $id);

    function create(array $data);
    function update(array $data, $id);
    function delete(int $id);
}
