<?php

namespace Database\Seeders;

use App\Models\Category;
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
        User::factory()->create([
            'email' => 'louis@gmail.com',
            'nickname' => 'louis.ng'
        ]);
        User::factory(30)->create();
        UserProfile::create([
            'user_id' => 1,
            'given_name' => 'Louis',
            'family_name' => 'Nguyen'
        ]);
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            VariationSeeder::class
        ]);
    }
}
