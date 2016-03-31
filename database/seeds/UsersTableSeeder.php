<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$defaultUsers = [
    		[
    			'id' => 1,
    			'name' => 'Isaac',
    			'email' => 'isaac.vanh@gmail.com',
    			'password' => bcrypt('the1saacvh'),
    		],
    		[
    			'id' => 2,
    			'name' => 'Aaron',
    			'email' => 'retteramj@gmail.com',
    			'password' => bcrypt('retter'),
    		],
    	];

        $this->command->info('Creating default users...');
        foreach ($defaultUsers as $user)
        {
        	// If it exists, update the user with a reserved id. Otherwise insert a user.
            $existing_user = User::find($user['id']);
            if($existing_user != null) 
            {
            	unset($user['id']);
                $existing_user->update($user);
            }
            else
            {
            	User::create($user);
            }
        }
        $this->command->info('Default users created.');
    }
}
