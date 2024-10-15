<?php

namespace Database\Seeders;

use App\Models\Topics;
use Illuminate\Database\Seeder;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Topics::create([
                "topic_name" => "PK",
                "slug" => "pk-pk",
                "parent_topic_id" => 0
            ]
        );
        Topics::create([
                "topic_name" => "child",
                "slug" => "child123",
                "parent_topic_id" => 1
            ]
        );
    }
}
