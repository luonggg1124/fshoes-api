<?php
namespace App\Services\Attribute;

interface AttributeServiceInterface
{
    function all();
    function create(array $data);
    function update(string|int $id, array $data);
    function find(int|string $id);
}
