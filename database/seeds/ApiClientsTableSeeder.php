<?php

use Illuminate\Database\Seeder;
use App\ApiClient;

class ApiClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$apiClients = [
    		['id' => 1, 'client_id' => 'Lanoda - Android Application', 'client_secret' => 'jghAFKxtS4KFm1EhT1YH9UO27JKRjahn'],
    		['id' => 2, 'client_id' => 'Lanoda - iOS Application', 'client_secret' => 'ngSZS5RrHOByFn91uqjZ8rW5obrjRr1Y']
    	];

    	foreach($apiClients as $apiClient) 
    	{
    		$existingApiClient = ApiClient::find($apiClient['id']);
    		if ($existingApiClient != null) {
				unset($apiClient['id']);
    			$existingApiClient->update($apiClient);
    		} else {
	    		ApiClient::create($apiClient);
    		}
    	}
		
		$this->command->info('');
    }
}
