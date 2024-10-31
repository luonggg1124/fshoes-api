<?php

namespace App\Services\Groups;


interface GroupsServiceInterface
{
    function getAll();
    function findById(int|string $id);

    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);

    function delete(int|string $id);
    function restore(int|string $id);
    function forceDelete(int|string $id);
}
