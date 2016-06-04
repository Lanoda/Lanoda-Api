<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$defaultRoles = [
    		[
    			'id' => 1,
    			'name' => 'Admin',
    			'description' => 'Give the user Admin priviledges.',
    		],
    	];

        $this->command->info('Creating default roles...');
        foreach ($defaultRoles as $role)
        {
        	// If it exists, update the user with a reserved id. Otherwise insert a user.
            $existingRole = Role::find($role['id']);
            if($existingRole != null) 
            {
            	unset($role['id']);
                $existingRole->update($role);
            }
            else
            {
				$newRole = Role::create($role);
            }
        }
        $this->command->info('Default roles created.');
    }
}
