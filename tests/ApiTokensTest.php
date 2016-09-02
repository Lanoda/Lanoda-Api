<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTokensTest extends TestCase 
{
	use DatabaseTransactions;

	public function testApiTokenCreate () 
	{
		$client = factory(App\ApiClient::class)->create();
		$user = factory(App\User::class)->create();

		$this->json('POST', '/api-token/request', [
			'client_id' => $client->client_id, 
			'client_secret' => $client->client_secret, 
			'email' => $user->email
		]);

		$this->assertResponseOk();

		$this->seeJsonStructure([
			'Content' => ['api_token']
		]);

		// $res_array = (array)json_decode($this->response->content());
		$responseData = json_decode($this->response->content(), true);

		$this->assertArrayHasKey('IsSuccess', $responseData);
		$this->assertTrue($responseData['IsSuccess']);

		$this->assertArrayHasKey('Content', $responseData);
		$this->assertNotNull($responseData['Content']);
		$this->assertEquals($client->client_id, $responseData['Content']['client_id']);
	}
}

