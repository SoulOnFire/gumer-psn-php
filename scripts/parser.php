<?php
	class PSNParser {
		private $psnURLS;
		private $region, $language;
		
		public function __construct($region, $language) {
			$this->region = $region;
			$this->language = $language;
			
			$this->psnURLS = array(
				'me_info' => 'https://vl.api.np.km.playstation.net/vl/api/v1/mobile/users/me/info',
				'friendlistURL' => 'https://{{region}}-prof.np.community.playstation.net/userProfile/v1/users/{{id}}/friendList?fields=onlineId,avatarUrl,plus,personalDetail,trophySummary&friendStatus=friend',
				'profileData' => 'https://{{region}}-prof.np.community.playstation.net/userProfile/v1/users/{{id}}/profile?fields=@default,relation,requestMessageFlag,presence,@personalDetail,trophySummary',
				
				'trophyData' => 'https://{{region}}-tpy.np.community.playstation.net/trophy/v1/trophyTitles?fields=@default&npLanguage={{lang}}&iconSize={{iconsize}}&platform=PS3,PSVITA,PS4&offset={{offset}}&limit={{limit}}&comparedUser={{id}}',
				'trophyDataList' => 'https://{{region}}-tpy.np.community.playstation.net/trophy/v1/trophyTitles/{{npCommunicationId}}/trophyGroups/{{groupId}}/trophies?fields=@default,trophyRare,trophyEarnedRate&npLanguage={{lang}}',
				'trophyGroupList' => 'https://{{region}}-tpy.np.community.playstation.net/trophy/v1/trophyTitles/{{npCommunicationId}}/trophyGroups/?npLanguage={{lang}}',
				'trophyInfo' => 'https://{{region}}-tpy.np.community.playstation.net/trophy/v1/trophyTitles/{{npCommunicationId}}/trophyGroups/{{groupId}}/trophies/{{trophyID}}?fields=@default,trophyRare,trophyEarnedRate&npLanguage={{lang}}',
				
				'conversations' => 'https://{{region}}-gmsg.np.community.playstation.net/groupMessaging/v1/users/{{id}}/messageGroups?fields=@default,messageGroupId,messageGroupDetail,totalUnseenMessages,totalMessages,latestMessage&npLanguage={{lang}}',
				'chat' => 'https://{{region}}-gmsg.np.community.playstation.net/groupMessaging/v1/messageGroups/{{chatId}}/messages?fields=@default,messageGroup,body&npLanguage={{lang}}',
				'message' => 'https://{{region}}-gmsg.np.community.playstation.net/groupMessaging/v1/messageGroups/{{chatId}}/messages'
			);
		
			foreach($this->psnURLS as $key => $val) {
				$this->psnURLS[$key] = str_replace("{{lang}}", $language, $this->psnURLS[$key]);
				$this->psnURLS[$key] = str_replace("{{region}}", $region, $this->psnURLS[$key]);
			}
		}
		
		public function initCURL() {
			$this->curl = curl_init();
			
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
		}
		
		public function closeCURL() {
			curl_close($this->curl);
		}
		
		public function getProfile($accessToken, $psnId) {
			$this->initCURL();
			$profileData = str_replace("{{id}}", $psnId, $this->psnURLS['profileData']);
			
			$headerData = $this->defaultGETHeader($accessToken);
			
			curl_setopt($this->curl, CURLOPT_URL, $profileData);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return json_encode($output, true);
		}
		
		public function getMyInfos($accessToken) {
			$this->initCURL();
			
			$headerData = array(
				'Access-Control-Request-Method: GET',
				'Accept-Language: ' .$this->language,
				'X-NP-ACCESS-TOKEN: ' .$accessToken,
				'User-Agent: Mozilla/5.0 (Linux; U; Android 4.3; ' .$this->language .'; C6502 Build/10.4.1.B.0.101) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 PlayStation App/1.60.5/' .$this->language .'/' .$this->language
			);
			
			curl_setopt($this->curl, CURLOPT_URL, $this->psnURLS['me_info']);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return json_encode($output, true);
		}
		
		public function getConversations($accessToken, $psnId){
			$this->initCURL();
			$conversations = str_replace("{{id}}", $psnId, $this->psnURLS['conversations']);
			
			$headerData = $this->defaultGETHeader($accessToken);
			
			curl_setopt($this->curl, CURLOPT_URL, $conversations);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return json_encode($output, true);
		}
		
		public function getChat($accessToken, $chatId){
			$this->initCURL();
			$chat = str_replace("{{chatId}}", $chatId, $this->psnURLS['chat']);
			
			$headerData = $this->defaultGETHeader($accessToken);
			
			curl_setopt($this->curl, CURLOPT_URL, $chat);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return json_encode($output, true);
		}
		
		public function sendMessage($accessToken, $chatId, $message) {
			$messageURL = str_replace("{{chatId}}", $chatId, $this->psnURLS['message']);
		
			$this->initCURL();
			
			$dataArray = array(
				'message' => array(
					'messageKind' => 1,
					'fakeMessageUid' => time() .rand(100, 999),
					'body' => $message
				)
			);
			
			$headerData = array(
				'Access-Control-Request-Method: POST',
				'Origin: http://psapp.dl.playstation.net',
				'Access-Control-Request-Headers: Origin, Accept-Language, Authorization, Content-Type, Cache-Control',
				'Accept-Language: ' .$this->language,
				'Authorization: Bearer ' .$accessToken,
				'Cache-Control: no-cache',
				'Accept-Encoding: gzip, deflate',
				'User-Agent: Mozilla/5.0 (Linux; U; Android 4.3; ' .$this->language .'; C6502 Build/10.4.1.B.0.101) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 PlayStation App/1.60.5/' .$this->language .'/' .$this->language,
				'Content-Type: multipart/mixed; boundary="abcdefghijklmnopqrstuvwxyz"'
			);
					
			$postData = '--abcdefghijklmnopqrstuvwxyz
Content-Type: application/json; charset=utf-8
Content-Description: message

' .json_encode($dataArray) .'
--abcdefghijklmnopqrstuvwxyz--';
			
			curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($this->curl, CURLOPT_URL, $messageURL);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
				'Access-Control-Request-Method: POST',
				'Origin: http://psapp.dl.playstation.net',
				'Access-Control-Request-Headers: Origin, Accept-Language, Authorization, Content-Type, Cache-Control',
				'Accept-Language: ' .$this->language,
				'Authorization: Bearer ' .$accessToken,
				'Cache-Control: no-cache',
				'Accept-Encoding: gzip, deflate',
				'User-Agent: Mozilla/5.0 (Linux; U; Android 4.3; ' .$this->language .'; C6502 Build/10.4.1.B.0.101) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 PlayStation App/1.60.5/' .$this->language .'/' .$this->language,
				'Content-Type: multipart/mixed; boundary="abcdefghijklmnopqrstuvwxyz"'
			));
						
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			echo json_encode($output, true);
		}
		
		public function getFriendlist($accessToken, $psnId){
			$this->initCURL();
			$friendlistURL = str_replace("{{id}}", $psnId, $this->psnURLS['friendlistURL']);
			
			$headerData = $this->defaultGETHeader($accessToken);
			
			curl_setopt($this->curl, CURLOPT_URL, $friendlistURL);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return $output;
		}
		
		private function defaultGETHeader($accessToken) {
			return array(
				'Access-Control-Request-Method: GET',
				'Origin: http://psapp.dl.playstation.net',
				'Access-Control-Request-Headers: Origin, Accept-Language, Authorization, Content-Type, Cache-Control',
				'Accept-Language: ' .$this->language,
				'Authorization: Bearer ' .$accessToken,
				'Cache-Control: no-cache',
				'X-Requested-With: com.scee.psxandroid',
				'User-Agent: Mozilla/5.0 (Linux; U; Android 4.3; ' .$this->language .'; C6502 Build/10.4.1.B.0.101) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 PlayStation App/1.60.5/' .$this->language .'/' .$this->language
			);
		}
		
		public function getTrophies($accessToken, $psnId, $iconSize, $offset, $limit) {
			$this->initCURL();
			
			$trophyData = str_replace("{{iconsize}}", $iconSize, $this->psnURLS['trophyData']);
			$trophyData = str_replace("{{id}}", $psnId, $trophyData);
			$trophyData = str_replace("{{offset}}", $offset, $trophyData);
			$trophyData = str_replace("{{limit}}", $limit, $trophyData);
			
			$headerData = $this->defaultGETHeader($accessToken);
			
			curl_setopt($this->curl, CURLOPT_URL, $trophyData);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headerData);
			
			$output = curl_exec($this->curl);
			$this->closeCURL();
			
			return json_encode($output, true);
		}
		
		public function print_r($r) {
			echo '<pre>';
			print_r($r);
			echo '</pre>';
		}
	}
?>