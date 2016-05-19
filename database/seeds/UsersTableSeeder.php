<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

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
    			'firstname' => 'Isaac',
    			'lastname' => 'Van Houten',
    			'email' => 'isaac.vanh@gmail.com',
    			'password' => bcrypt('the1saacvh'),
    		],
    		[
    			'id' => 2,
    			'firstname' => 'Aaron',
    			'lastname' => 'Retter',
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
				$existing_user = User::create($user);
            }

            // Add the 'Admin' role to user with Id '1'
            $admin_role = $existing_user->roles->where('id', 1);
            if ($admin_role == null) {
                $existing_user->roles()->attach(Role::find(1));
            }
            $existing_user->save();
        }
        $this->command->info('Default users created.');
    }
}
