<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{

    function all();
    function find(int $id);

    function create(array $data);
    function update($id, array $data);
    function delete(int $id);
    function query();
}
