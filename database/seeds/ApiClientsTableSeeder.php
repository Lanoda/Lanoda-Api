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
    		'Lanoda - Android Application',
    		'Lanoda - iOS Application'
    	];

    	foreach($apiClients as $apiClientId) 
    	{
    		$apiClient = [
    			'client_id' => $apiClientId,
    			'client_secret' => str_random(64)
    		];
    		$existingApiClient = ApiClient::where('client_id', $apiClientId)->first();
    		if ($existingApiClient != null) {
    			$existingApiClient->update($apiClient);
    		} else {
	    		ApiClient::create($apiClient);
    		}
    	}
    }
}
