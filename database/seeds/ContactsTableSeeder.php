<?php

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;
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

            $timeStart = microtime(true);

            $output = new ConsoleOutput();
            $this->command->info('');
            $this->command->line('Creating fake Contacts...');

            $startRange = 1;
            $endRange = 30;
            $progressBar = new ProgressBar($output, $endRange);

            $faker = Faker\Factory::create();
            foreach (range($startRange, $endRange) as $index)
            {
                $firstName = $faker->firstName();
                $middleName = $faker->firstName();
                $lastName = $faker->lastName();

                // Insert the Contact
                $existingContact = Contact::find($index);

                $fakeContact = [
                    'user_id'   => $faker->numberBetween(1, 2),
                    'firstname' => $firstName,
                    'middlename'=> $middleName,
                    'lastname'  => $lastName,
                    'phone'     => $faker->phoneNumber(),
                    'email'     => $firstName . '.' . $lastName . '@' . $faker->freeemaildomain(),
                    'address'   => $faker->address(),
                    'age'       => $faker->numberBetween(18, 100),
                    'birthday'  => $faker->date(),
                ];

                if ($existingContact != null) {
                    $existingContact->update($fakeContact);
                } else {
                    Contact::create($fakeContact);
                }

                $progressBar->advance();
            }

            $progressBar->finish();

            $totalTime = microtime(true) - $timeStart;
            $this->command->info('');
            $this->command->info('Time: ' . $totalTime . ' seconds');
            $this->command->info('');
            $this->command->info('');
        }
    }
}
