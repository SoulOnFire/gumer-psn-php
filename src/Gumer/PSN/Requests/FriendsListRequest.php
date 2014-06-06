<?php namespace Gumer\PSN\Requests;

class FriendsListRequest extends AbstractAuthenticatedRequest {

	/**
	 * @var string
	 */
	protected $uri = 'https://{{region}}-prof.np.community.playstation.net/userProfile/v1/users/{{id}}/friendList?fields=onlineId,avatarUrl,plus,personalDetail,trophySummary&friendStatus=friend';

	/**
	 * @param mixed $userId
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->params['id'] = $userId;
	}

}