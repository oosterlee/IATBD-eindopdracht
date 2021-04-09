<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
    		'name' => 'Admin',
    		'email' => 'admin@royoosterlee.nl',
    		'password' => bcrypt('laravel'),
    		'blocked' => false,
    		'is_admin' => true,
    	]);
    	// DB::table('users')->insert([
    	// 	'name' => 'test2',
    	// 	'email' => 'test2@test.com',
    	// 	'password' => bcrypt('laravel'),
    	// 	'blocked' => false,
    	// 	'is_admin' => true,
    	// ]);
    	// DB::table('users')->insert([
    	// 	'name' => 'test3',
    	// 	'email' => 'test3@test.com',
    	// 	'password' => bcrypt('laravel'),
    	// 	'blocked' => true,
    	// 	'is_admin' => false,
    	// ]);
        // \App\Models\User::factory(10)->create();
    }
}
