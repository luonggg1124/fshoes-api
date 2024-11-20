<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function create(array $data, array $options = []);
    public function findByNickname(string $nickname);
    function update(string|int $id, array $data, array $options = []);
    function delete(string|int $id);
    public function all();
    function getFavoriteProduct();
    function addFavoriteProduct(int|string $productId);
    function removeFavoriteProduct(int|string $productId);
    function updateProfile(array $data);
    function userHasOrderCount();
}
