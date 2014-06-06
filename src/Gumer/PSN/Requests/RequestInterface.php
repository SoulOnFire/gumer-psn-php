<?php namespace Gumer\PSN\Requests;

interface RequestInterface {

	/**
	 * @return string
	 */
	public function getUri();

	/**
	 * @return string
	 */
	public function getMethod();

	/**
	 * Get the body of the request.
	 * 
	 * @return string|array
	 */
	public function getBody();

	/**
	 * Get any headers that need setting.
	 * 
	 * @return array
	 */
	public function getHeaders();

}