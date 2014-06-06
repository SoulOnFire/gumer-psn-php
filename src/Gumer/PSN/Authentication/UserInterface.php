<?php namespace Gumer\PSN\Authentication;

interface UserInterface {

	/**
	 * Get the access token.
	 * 
	 * @return string
	 */
	public function getAccessToken();

}