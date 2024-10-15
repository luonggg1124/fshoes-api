<?php

namespace Database\Seeders;

use App\Models\Modules;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use function GuzzleHttp\json_encode;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Modules::create([
            "module_name"=>"users",
            "module_actions"=>json_encode(["view" , "add","update","delete" , "restore", "forceDelete"])
        ]);
        Modules::create([
            "module_name"=>"category",
            "module_actions"=>json_encode(["view" , "add","update","delete" , "restore", "forceDelete"])
        ]);
    }
}
