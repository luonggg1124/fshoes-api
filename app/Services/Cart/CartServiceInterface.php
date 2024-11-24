<?php
namespace App\Services\Cart;

interface CartServiceInterface {
    function getAll();
    function findById(int|string $id);
    function create(array $data, array $option = []);
    function update(int|string $id,array $data, array $option = []);
    function delete(int|string $id);
}