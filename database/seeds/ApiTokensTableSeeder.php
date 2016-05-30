<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\ApiToken;

class ApiTokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apiToken = [
            'api_token' => str_random(32),
            'client_id' => 'Lanoda - Android Application',
            'user_id' => '1',
            'expires' => Carbon::now()->addDay()
        ];

        ApiToken::create($apiToken);
    }
}
