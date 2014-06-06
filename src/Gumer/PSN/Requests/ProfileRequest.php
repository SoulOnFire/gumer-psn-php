<?php namespace Gumer\PSN\Requests;

class ProfileRequest extends AbstractAuthenticatedRequest {

	/**
	 * @var string
	 */
	protected $uri = 'https://{{region}}-prof.np.community.playstation.net/userProfile/v1/users/{{id}}/profile?fields=@default,relation,requestMessageFlag,presence,@personalDetail,trophySummary';

	/**
	 * @param mixed $userId
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->params['id'] = $userId;
	}

}