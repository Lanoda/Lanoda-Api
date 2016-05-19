<?php

class UsersTest extends TestCase 
{

	public function testDatabase () 
	{
		$this->seeInDatabase('users', ['email' => 'isaac.vanh@gmail.com']);
		$this->seeInDatabase('users', ['email' => 'retteramj@gmail.com']);
	}
}
