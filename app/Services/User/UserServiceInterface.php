<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function login(string $email, string $password);
    public function create(array $user, array $options = []);
    public function findByNickname(string $nickname);
    function update(string $nickname, array $data, array $options = []);
    public function all();
   
}
