<?php

namespace App\Services\OrderDetail;

interface OrderDetailServiceInterface
{
    function getAll($params);
    function findById(int|string $id);

    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);

}
