<?php

namespace App\Services\Wishlist;

interface WishlistServiceInterface
{
    function getAll();
    function findByUserID(int|string $idUser);
    function create(array $data, array $option = []);
    function delete(int|string $id);
}
