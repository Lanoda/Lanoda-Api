<?php

use Illuminate\Database\Seeder;
use App\Contact;
use App\Note;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'test') {
            $this->command->info('Creating fake Contacts...');

            $faker = Faker\Factory::create();
            foreach (range(1, 30) as $index)
            {
                $firstName = $faker->firstName();
                $middleName = $faker->firstName();
                $lastName = $faker->lastName();
                $urlName = str_replace(' ', '', $firstName.'-'.$middleName.'-'.$lastName);

                // Insert the Contact
                $existingContact = Contact::find($index);
                if ($existingContact != null) {
                    $existingContact->update([
                        'user_id'   => $faker->numberBetween(1, 2),
                        'url_name'  => $urlName,
                        'firstname' => $firstName,
                        'middlename'=> $middleName,
                        'lastname'  => $lastName,
                        'phone'     => $faker->phoneNumber(),
                        'email'     => $faker->email(),
                        'address'   => $faker->address(),
                        'age'       => $faker->numberBetween(18, 100),
                        'birthday'  => $faker->date(),
                    ]);
                } else {
                    Contact::create([
                        'id'        => $index,
                        'user_id'   => $faker->numberBetween(1, 2),
                        'url_name'  => $urlName,
                        'firstname' => $firstName,
                        'middlename'=> $middleName,
                        'lastname'  => $lastName,
                        'phone'     => $faker->phoneNumber(),
                        'email'     => $faker->email(),
                        'address'   => $faker->address(),
                        'age'       => $faker->numberBetween(18, 100),
                        'birthday'  => $faker->date(),
                    ]);
                }
            }

            $this->command->info('Creating fake Contacts...');
        }
    }
}
