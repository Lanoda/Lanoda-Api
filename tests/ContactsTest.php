<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactsTest extends TestCase 
{
	use DatabaseTransactions;

	public function testContactsDelete() 
	{
		$testApiToken = factory(App\ApiToken::class)->create();
		$contact = factory(App\Contact::class)->create();

		// Link api token and Contact's user
		$testApiToken->user_id = $contact->user_id;
		$testApiToken->save();

		$this->call('DELETE', '/contacts/'.$contact->id, [], [], [], ['HTTP_lanoda-api-token' => $testApiToken->api_token]);
		$this->assertEquals(204, $this->response->getStatusCode());

		$exists = App\Contact::find($contact->id);
		$this->assertNull($exists);
	}

	public function testContactsGetPaged()
	{
    	$faker = Faker\Factory::create();
		$testApiToken = factory(App\ApiToken::class)->create();

		$contactNumber = $faker->numberBetween(1, 15);
		for($i = 0; $i < $contactNumber; $i++) {
			$contact = factory(App\Contact::class)->create();
			$contact->user_id = $testApiToken->user_id;
			$contact->save();
		}

		// Test Get Contacts within size parameters
		$contactsCount = App\Contact::all()->where('user_id', $testApiToken->user_id)->count();
		$pageSize = $faker->numberBetween(1, 20);
		$pageIndex = $faker->numberBetween(0, $contactsCount / $pageSize);

		$this->call(
			'GET', 
			'/contacts?pageSize='.$pageSize.'&pageIndex='.$pageIndex,
			[], 
			[], 
			[], 
			['HTTP_lanoda-api-token' => $testApiToken->api_token]
		);

		$result = json_decode($this->response->content(), true);

		$this->assertResponseOk();
		$this->assertNotNull($result['Content']);
		$this->assertNotNull($result['Content']['Contacts']);
		$this->assertNotNull($result['Content']['TotalResults']);
		$this->assertNotNull($result['Content']['PageIndex']);
		$this->assertNotNull($result['Content']['PageSize']);

		$contactsResultLength = count($result['Content']['Contacts']);

		$this->assertTrue($contactsResultLength <= $result['Content']['PageSize']);
		$this->assertTrue($contactsResultLength <= $result['Content']['TotalResults']);
		$this->assertEquals($result['Content']['PageIndex'], $pageIndex);
		$this->assertEquals($result['Content']['TotalResults'], $contactsCount);
		
	}

	public function testContactsPut()
	{
    	$faker = Faker\Factory::create();
		$testApiToken = factory(App\ApiToken::class)->create();
		$contact = factory(App\Contact::class)->create();

		// Link api token and Contact's user
		$testApiToken->user_id = $contact->user_id;
		$testApiToken->save();

		$newFirstname = $faker->firstname;
		$contact->firstname = $newFirstname;

		$this->call('PUT', '/contacts/' . $contact->id, $contact->toArray(), [], [], ['HTTP_lanoda-api-token' => $testApiToken->api_token]);
		$responseData = json_decode($this->response->content(), true);
		$this->assertEquals($newFirstname, $responseData['Content']['firstname']);
		$this->assertResponseOk();
	}
}

