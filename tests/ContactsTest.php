<?php

class ContactsTest extends TestCase 
{

	public function testContactsGet () 
	{
		$response = $this->call('GET', '/contacts');
		$this->assertEquals(200, $response->status())
	}
}

