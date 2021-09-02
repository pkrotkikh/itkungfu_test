<?php

namespace Database\Seeders;

use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);
        Artisan::call('storage:link');
        Post::factory()->count(40)->create();
    }
}
