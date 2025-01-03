<?php

namespace App\Services\Order;

use Illuminate\Http\UploadedFile;

interface OrderServiceInterface
{
    function getAll();
    function findById(int|string $id);

    function create(array $data, array $option = []);
    function createAsAdmin(array $data);
    function update(int|string $id,array $data, array $option = []);
   
}
