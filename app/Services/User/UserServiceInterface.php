<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function create(array $data, array $options = []);
    public function findByNickname(string $nickname);
    function update(string $nickname, array $data, array $options = []);
    public function all();

}
