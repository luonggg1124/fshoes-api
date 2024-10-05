<?php

namespace App\Services\OrderHistory;

interface OrderHistoryServiceInterface
{
    function getAll($params);
    function findById(int|string $id);
    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);

}
