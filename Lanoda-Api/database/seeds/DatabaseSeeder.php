<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UserTableSeeder');

        DB::table('users')->insert([
        	'email' => 'isaac.vanh@gmail.com',
        	'password' => Hash::make('the1saacvh'),
        ]);
    }
}
