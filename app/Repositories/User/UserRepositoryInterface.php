<?php

namespace App\Repositories\User;


use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    function createProfile(array $data);
    function updateProfile(int|string $userId, array $data);
    public function findByNickname(string $nickname);
}
