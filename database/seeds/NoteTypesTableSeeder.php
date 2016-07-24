<?php

use Illuminate\Database\Seeder;
use App\NoteType;

class NoteTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$defaultNoteTypes = [
    		[
    			'id' => 1,
    			'name' => 'General',
    			'description' => 'General-Purpose Note.',
                'icon' => 'note-general'
    		],
    	];

        $this->command->info('Creating default NoteTypes...');
        foreach ($defaultNoteTypes as $noteType)
        {
        	// If it exists, update the user with a reserved id. Otherwise insert a user.
            $existingNoteType = NoteType::find($noteType['id']);
            if($existingNoteType != null) 
            {
            	unset($noteType['id']);
                $existingNoteType->update($noteType);
            }
            else
            {
				$newNoteType = NoteType::create($noteType);
            }
        }
        $this->command->info('Default NoteTypes created.');
    }
}
