<?php

use Illuminate\Database\Seeder;
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
    	Contact::truncate();

    	Eloquent::unguard();

        $this->call(ContactsTableSeeder::class);
    }
}
