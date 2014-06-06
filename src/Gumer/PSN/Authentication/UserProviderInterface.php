<?php namespace Gumer\PSN\Authentication;

interface UserProviderInterface {

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function attemptLoginWithCredentials($username, $password);
	
}