<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Contact;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        foreach (range(1, 30) as $index)
        {
            // Generate a name.
        	$fullname = $faker->name(1);
        	$arrayList = explode(' ', $fullname);
        	$name = ['First', 'Last'];
        	if (substr($arrayList[0], -1) == ".") {
        		$name[0] = $arrayList[1];
        		$name[1] = $arrayList[2];
        	} else {
        		$name[0] = $arrayList[0];
        		$name[1] = $arrayList[1];
        	}

            // Insert the Contact
        	Contact::create([
        		'firstname' => $name[0],
        		'lastname' => $name[1],
                'some_bool' => $faker->boolean(),
        	]);
        }
    }
}
