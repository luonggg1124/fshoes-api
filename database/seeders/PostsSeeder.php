<?php

namespace Database\Seeders;

use App\Models\Posts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Posts::create([
            "title" => "Title 1",
            "slug"=>"title-1",
            "content"=>"<h1>OK con de</h1>",
            "topic_id"=>1,
            "author_id"=>1,
        ]);
    }
}
