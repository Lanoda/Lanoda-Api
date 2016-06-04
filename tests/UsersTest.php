<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase 
{
	public function testDatabase () 
	{
		$this->seeInDatabase('users', ['email' => 'isaac.vanh@gmail.com']);
		$this->seeInDatabase('users', ['email' => 'retteramj@gmail.com']);
	}
}
