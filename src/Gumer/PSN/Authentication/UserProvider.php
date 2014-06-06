<?php namespace Gumer\PSN\Authentication;

use Gumer\PSN\Http\Connection;
use Gumer\PSN\Requests\CustomRequest;
use Gumer\PSN\Requests\AuthenticationOAuthRequest;
use Gumer\PSN\Requests\AuthenticationSigninRequest;
use Gumer\PSN\Requests\AuthenticationSigninPostRequest;

class UserProvider implements UserProviderInterface {
	
	/**
	 * @var \Gumer\PSN\Http\Connection
	 */
	protected $connection;

	/**
	 * @param \Gumer\PSN\Http\Connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function attemptLoginWithCredentials($username, $password)
	{
		$response1 = $this->connection->call(new AuthenticationSigninRequest);
		$response2 = $this->handleStage2($response1->getLocation(), $username, $password);
		$authCode  = $this->handleStage3($response2->getLocation());
		$oauth     = $this->handleStage4($authCode)->getBody(true);

		return $this->createUser($oauth);
	}

	protected function createUser($oauth)
	{
		$oauth = json_decode($oauth, true);

		return new User(array_get($oauth, 'access_token'));
	}

	protected function handleStage2($location, $username, $password)
	{
		$request = new AuthenticationSigninPostRequest;
		$request->setLocation($location);
		$request->setEmail($username);
		$request->setPassword($password);

		return $this->connection->call($request);
	}

	protected function handleStage3($location)
	{
		$request = new CustomRequest;
		$request->setUri($location);

		$response = $this->connection->call($request);
		$location = urldecode($response->getLocation());

		if (false === strpos($location, 'authCode='))
		{
			throw new \UnexpectedValueException;
		}

		return substr($location, strpos($location, 'authCode=') +9, 6);
	}

	protected function handleStage4($authCode)
	{
		$request = new AuthenticationOAuthRequest($authCode);

		return $this->connection->call($request);
	}

}