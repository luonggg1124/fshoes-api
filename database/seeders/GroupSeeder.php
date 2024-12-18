<?php

namespace Database\Seeders;

use App\Models\Groups;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Database\Seeder;
use function GuzzleHttp\json_encode;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws InvalidArgumentException
     */
    public function run(): void
    {
        $permissions = json_encode([
            "user" => ["delete"],
            "product" => ["view", "add"],
        ]);
        Groups::create([
            "group_name" => "Nhân Viên",
            "permissions" => $permissions,
        ]);
        Groups::create([
            "group_name" => "Quản Trị",
            "permissions" => $permissions,
        ]);
    }
}
