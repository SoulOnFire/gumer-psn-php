<?php namespace Gumer\PSN\Authentication;

class User implements UserInterface {

	/**
	 * @var string
	 */
	protected $accessToken;

	/**
	 * @param string $accessToken
	 */
	public function __construct($accessToken)
	{
		$this->accessToken = $accessToken;
	}

	/**
	 * Get the access token.
	 * 
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}

}