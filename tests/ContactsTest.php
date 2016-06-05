<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactsTest extends TestCase 
{
	use DatabaseTransactions;

	public function testContactsGet () 
	{
		$testApiToken = factory(App\ApiToken::class)->create();

		$this->call('GET', '/users/'.$testApiToken->user_id.'/contacts', [], [], [], ['HTTP_lanoda-api-token' => $testApiToken->api_token]);
		$this->assertResponseOk();
	}
}

