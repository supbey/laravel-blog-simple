<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $this->call(PostsTableSeeder::class);

        Model::unguard();

        //$this->call('TagsTableSeeder');
        //$this->call('PostsTableSeeder');
        $this->call(TagsTableSeeder::class);
        $this->call(PostsTableSeeder::class);

        Model::reguard();

    }
}


