<?php

namespace App\Services\Attribute\Value;

interface AttributeValueServiceInterface
{
    public function index(int|string $aid);
    public function create(int|string $aid,array $data);
    public function createMany(int|string $aid,array $data);
    public function find(int|string $aid,int|string $id);
    public function update(int|string $aid,int|string $id, array $data);
    public function delete(int|string $aid,int|string $id);
}
