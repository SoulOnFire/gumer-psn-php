<?php
	class PSNLogIn {
		private $psnURLS, $psnVars;
		private $csrfToken;
		private $email, $password;
		private $accessToken, $refreshToken;
		private $authCodeRegex, $csrfTokenRegex;
		private $cookieDir, $cookieFile;
		
		public function __construct() {
			$this->cookieDir = "./tmp/";
			$this->cookieFile = $this->cookieDir ."psn_" .$_SERVER["REMOTE_ADDR"] .".cookie";
			
			$this->psnVars = array(
				'redirectURL' => 'com.scee.psxandroid.scecompcall://redirect',
				'client_id' => 'b0d0d7ad-bb99-4ab1-b25e-afa0c76577b0',
				'scope' => 'sceapp',
				'scope_psn' => 'psn:sceapp',
				'csrfToken' => '',
				'authCode' => '',
				'client_secret' => 'Zo4y8eGIa3oazIEp',
				'duid' => '00000005006401283335353338373035333434333134313a433635303220202020202020202020202020202020'
			);
			
			$this->psnURLS = array(
				'signIn' => 'https://reg.api.km.playstation.net/regcam/mobile/sign-in.html?redirectURL=' .$this->psnVars['redirectURL'] .'&client_id=' .$this->psnVars['client_id'] .'&scope=' .$this->psnVars['scope'],
				'signInPost' => 'https://reg.api.km.playstation.net/regcam/mobile/signin',
				'oauth' => 'https://auth.api.sonyentertainmentnetwork.com/2.0/oauth/token'
			);
			
			$this->authCodeRegex = '/authCode\=([0-9A-Za-z]*)(?=[\'])/i';
			$this->csrfTokenRegex = '/<input.*?csrfToken.*?value="(.*?)".*?>/i';
		}
		
		public function setEmail($email) {
			$this->email = $email;
		}
		
		public function setPassword($password) {
			$this->password = $password;
		}
		
		public function initCURL() {
			$this->curl = curl_init();
		}
		
		public function closeCURL() {
			curl_close($this->curl);
		}
		
		public function login() {
			$this->initCURL();
			
			curl_setopt($this->curl, CURLOPT_URL, $this->psnURLS['signIn']);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookieFile);
			
			$output = $this->curl_exec();			
			preg_match($this->csrfTokenRegex, $output, $matches);
			
			if (count($matches) > 0) {
				// echo "CSRF-Token: " .$matches[1] ."<br/>\n";
				
				$this->psnVars['csrfToken'] = $matches[1];
				$this->getAuthCode($this->psnVars['csrfToken']);
			}
			
			$this->closeCURL();
			
			if (empty($this->psnVars['csrfToken'])) {
				return -1;
			
			} else if (empty($this->psnVars['authCode'])) {
				return -2;
			
			} else if (empty($this->psnVars['csrfToken']) || empty($this->psnVars['authCode'])) {
				return -3;
				
			} else {
				return array($this->accessToken, $this->refreshToken);
			}
		}
		
		private function getAuthCode($csrfToken) {
			$postData = array(
				'email' => $this->email,
				'password' => $this->password,
				'csrfToken' => $csrfToken,
				'client_id' => $this->psnVars['client_id'],
				'scope' => $this->psnVars['scope'],
				'redirectURL' => $this->psnVars['redirectURL'],
				'locale' => ''
			);
			
			curl_setopt($this->curl, CURLOPT_URL, $this->psnURLS['signInPost']);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postData));
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookieFile);
			
			$output = $this->curl_exec();
			preg_match($this->authCodeRegex, $output, $matches);
			
			if (count($matches) > 0) {
				// echo "AUTH-Code: " .$matches[1] ."<br/>\n";
				
				$this->psnVars['authCode'] = $matches[1];
				$this->getAccessToken($this->psnVars['authCode']);
			}
		}
		
		private function getAccessToken($authCode) {
			$dataArray = array(
				'grant_type' => 'authorization_code',
				'client_id' => $this->psnVars['client_id'],
				'client_secret' => $this->psnVars['client_secret'],
				'code' => $authCode,
				'redirect_uri' => $this->psnVars['redirectURL'],
				'state' => 'x',
				'scope' => $this->psnVars['scope_psn'],
				'duid' => $this->psnVars['duid']
			);
			
			$postData = http_build_query($dataArray);
			
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($this->curl, CURLOPT_URL, $this->psnURLS['oauth']);
			
			$output = $this->curl_exec();
			$jsonData = json_decode($output, true);
			
			$this->accessToken = $jsonData['access_token'];
			$this->refreshToken = $jsonData['refresh_token'];
			
			// echo "Access-Token: " .$this->accessToken ."<br/>\n";
			// echo "Refresh-Token: " .$this->refreshToken ."<br/>\n";
		}
		
		private function curl_exec() {
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
			
			/*
			curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
			curl_setopt($this->curl, CURLOPT_NOBODY, FALSE);
			curl_setopt($this->curl, CURLOPT_FORBID_REUSE, FALSE);
			*/
			
			return curl_exec($this->curl);
		}
	}
?>