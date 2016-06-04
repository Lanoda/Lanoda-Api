<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\ApiToken;
use App\User;

class ApiTokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::all()->pluck('id');
        foreach ($userIds as $userId) 
        {
            $clientId = 'Lanoda - Android Application';
            $apiToken = [
                'api_token' => str_random(32),
                'client_id' => $clientId,
                'user_id' => $userId,
                'expires' => Carbon::now()->addWeek()
            ];

            $existingToken = ApiToken::where(['client_id' => $clientId, 'user_id' => $userId])->first();
            if ($existingToken != null) 
            {
                $existingToken->update($apiToken);
            } 
            else
            {
                ApiToken::create($apiToken);
            }
        }
    }
}
