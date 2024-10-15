<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Groups;
use App\Models\User;
use App\Models\User\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PHPUnit\Metadata\Group;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           ModulesSeeder::class,
           GroupSeeder::class
        ]);
        User::factory()->create([
            'email' => 'louis@gmail.com',
            'nickname' => 'louis.ng',
            'group_id'=>'1'
        ]);
        User::factory(20)->create();
        // User::factory(30)->create();
//        UserProfile::create([
//            'user_id' => 1,
//            'given_name' => 'Louis',
//            'family_name' => 'Nguyen'
//        ]);
        User::factory()->create([
            'email' => 'quoc@gmail.com',
            'nickname' => 'quocaa'
        ]);
        // User::factory(30)->create();
        UserProfile::create([
            'user_id' => 2,
            'given_name' => 'Quoc',
            'family_name' => 'Anh'
        ]);
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            VariationSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
