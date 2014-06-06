<?php namespace Gumer\PSN\Authentication;

class Manager {
	
	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @var \Gumer\PSN\Authentication\UserProviderInterface
	 */
	protected $provider;

	/**
	 * @var \Gumer\PSN\Authentication\UserInterface
	 */
	protected $user;

	/**
	 * @param \Gumer\PSN\Authentication\UserProviderInterface $provider
	 */
	private function __construct(UserProviderInterface $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * @param \Gumer\PSN\Authentication\UserProviderInterface $provider
	 * @return \Gumer\PSN\Authentication\Manager
	 * @throws \RuntimeException
	 */
	public static function instance(UserProviderInterface $provider = null)
	{
		if (is_null(static::$instance))
		{
			if (is_null($provider))
			{
				throw new \RuntimeException('Gumer PSN Authentication Manager not been initialised properly.');
			}

			static::$instance = new static($provider);
		}

		return static::$instance;
	}

	/**
	 * Attempt to login with a username and a password.
	 * 
	 * @param string $username
	 * @param string $password
	 * @param bool $remember
	 * @return \Gumer\PSN\Authentication\UserInterface|null
	 */
	public function attempt($username, $password, $remember = true)
	{
		$user = $this->provider->attemptLoginWithCredentials($username, $password);

		if ((! $user) or !($user instanceof UserInterface))
		{
			return false;
		}

		if ($remember)
		{
			$this->be($user);
		}

		return $user;
	}

	/**
	 * Set the current user.
	 * 
	 * @param \Gumer\PSN\Authentication\UserInterface $user
	 * @return void
	 */
	public function be(UserInterface $user)
	{
		$this->user = $user;
	}

	/**
	 * Get the current user.
	 * 
	 * @return \Gumer\PSN\Authentication\UserInterface|null
	 */
	public function user()
	{
		return $this->user;
	}

}