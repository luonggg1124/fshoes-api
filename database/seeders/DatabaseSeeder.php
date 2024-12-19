<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\User\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            'email' => 'luongnm1124@gmail.com',
            'nickname' => 'louis.ng',
            'group_id'=>'1',
            'is_admin' => true
        ]);
        UserProfile::create([
            'user_id' => 1,
            'given_name' => 'Luong',
            'family_name' => 'Nguyen'
        ]);
        // User::factory(30)->create();
//        UserProfile::create([
//            'user_id' => 1,
//            'given_name' => 'Louis',
//            'family_name' => 'Nguyen'
//        ]);
        User::factory()->create([
            'email' => 'quoc@gmail.com',
            'nickname' => 'quocaa',
            'is_admin' => true
        ]);
        // User::factory(30)->create();
        UserProfile::create([
            'user_id' => 2,
            'given_name' => 'Quoc',
            'family_name' => 'Anh'
        ]);
        User::factory()->create([
            'email' => 'long@gmail.com',
            'nickname' => 'longv',
            'is_admin' => true
        ]);
        // User::factory(30)->create();
        UserProfile::create([
            'user_id' => 3,
            'given_name' => 'Long',
            'family_name' => 'Vu',
            
        ]);
        User::factory()->create([
            'email' => 'linh@gmail.com',
            'nickname' => 'linhn',
            'is_admin' => true
        ]);
        // User::factory(30)->create();
        UserProfile::create([
            'user_id' => 4,
            'given_name' => 'Linh',
            'family_name' => 'Nguyen'
        ]);
        User::factory()->create([
            'email' => 'thai@gmail.com',
            'nickname' => 'thain',
            'is_admin' => true
        ]);
        // User::factory(30)->create();
        UserProfile::create([
            'user_id' => 5,
            'given_name' => 'Thai',
            'family_name' => 'Nguyen',
           
        ]);
        User::factory(50)->create();
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            VariationSeeder::class,
            OrderSeeder::class,
            SaleSeeder::class,
            TopicsSeeder::class,
            PostsSeeder::class,
            VoucherSeeder::class,
            ImageSeeder::class
        ]);
        

    }
}
