<?php namespace Gumer\PSN\Requests;

class CustomRequest extends AbstractRequest {

	/**
	 * @param string $uri
	 * @return static
	 */
	public function setUri($uri)
	{
		$this->uri = (string) $uri;

		return $this;
	}

	/**
	 * @param string $method
	 * @return static
	 */
	public function setMethod($method)
	{
		$this->method = (string) $method;

		return $this;
	}

	/**
	 * @param array data
	 * @return static
	 */
	public function setBody(array $data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @param array $headers
	 * @return static
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;

		return $this;
	}

}