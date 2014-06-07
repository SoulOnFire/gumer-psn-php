<?php

use Mockery as m;
use Gumer\PSN\Authentication\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testAttemptMethod()
	{
		$userProvider = m::mock('Gumer\PSN\Authentication\UserProviderInterface');
		$userInstance = m::mock('Gumer\PSN\Authentication\UserInterface');
		$manager      = Manager::instance($userProvider);
		$testUsername = '1234abcd';
		$testPassword = 'abcd123';

		$userProvider
			->shouldReceive('attemptLoginWithCredentials')
			->with($testUsername, $testPassword)
			->andReturn($userInstance);

		$response = $manager->attempt($testUsername, $testPassword);

		$this->assertSame($userInstance, $response);
	}

	public function testAssigningUser()
	{
		$userProvider = m::mock('Gumer\PSN\Authentication\UserProviderInterface');
		$userInstance = m::mock('Gumer\PSN\Authentication\UserInterface');
		$manager      = Manager::instance($userProvider);

		$manager->be($userInstance);

		$this->assertSame($userInstance, $manager->user());
	}

}