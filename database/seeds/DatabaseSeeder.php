<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Contact;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::truncate();
    	// Contact::truncate();

    	Eloquent::unguard();

        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(ApiClientsTableSeeder::class);
        $this->call(ApiTokensTableSeeder::class);
    }
}
