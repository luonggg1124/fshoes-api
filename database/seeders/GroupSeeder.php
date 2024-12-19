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
        $permissionAdmin = json_encode([
            "user" => ['add','view','delete','update'],
            'product' => ['view','add','update','delete'],
            'voucher' => ['view','add','uo','update'],
            "dashboard" => ['add','view','delete','update'],
            'topic' => ['view','add','update','delete'],
            'post' => ['view','add','update','update'],
            'order' => ['view','add','delete','update'],
            'group' => ['view','add','delete','update'],
            'media' => ['view','add','delete','update'],
            'discount' => ['view','add','delete','update'],
            'review' => ['view','add','delete','update'],
            'sale' => ['view','add','delete','update'],
        ]);
        Groups::create([
            "group_name" => "Quản Trị",
            "permissions" => $permissionAdmin,
        ]);
        Groups::create([
            "group_name" => "Nhân viên",
            "permissions" => $permissions,
        ]);
        Groups::create([
            "group_name" => "Khách Hàng",
            "permissions" => json_encode([]),
        ]);
    }
}
