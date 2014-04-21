<?php
	require_once 'scripts/login.php';
	require_once 'scripts/parser.php';
	require_once 'scripts/Toro.php';
	require_once 'site/site.php';
	
	ToroHook::add('404', function() {
		echo 'Not found';
	});

	Toro::serve(array(
		'/' => 'Site',
		'/login' => 'Login',
		'/refresh' => 'Refresh',
		'/me' => 'Me',
		'/profile' => 'Profile',
		'/friends' => 'Friends',
		'/trophies' => 'Trophy',
		'/conversations' => 'Conversation',
		'/chat' => 'Chat',
		'/chat/message' => 'Message'
	));
	
	class Message {
		function post() {
			$accessToken = $_POST['accessToken'];
			$chatId = $_POST['chatId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			$message = $_POST['message'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->sendMessage($accessToken, $chatId, $message);
		}
	}
	
	class Chat {
		function post() {
			$accessToken = $_POST['accessToken'];
			$chatId = $_POST['chatId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->getChat($accessToken, $chatId);
		}
	}
	
	class Conversation {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
	
			$parser = new PSNParser($region, $language);
	
			echo $parser->getConversations($accessToken, $profileId);
		}
	}
			
	class Trophy {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			$iconSize = $_POST['iconSize'];
			$offset = $_POST['offset'];
			$limit = $_POST['limit'];
			
			$parser = new PSNParser($region, $language);
	
			echo $parser->getTrophies($accessToken, $profileId, $iconSize, $offset, $limit);
		}
	}
	
	class Friends {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->getFriendlist($accessToken, $profileId);
		}
	}
	
	class Profile {
		function post() {			
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->getProfile($accessToken, $profileId);
		}
	}
			
	class Me {
		function get() {
			$accessToken = $_POST['accessToken'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->getMyInfos($accessToken);
		}
	}
	
	class Refresh {
		function post() {
			$refreshToken = $_POST['refreshToken'];
			
			$login = new PSNLogIn();
			
			echo $login->refreshTokens($refreshToken);
		}
	}
	
	class Login {
		private $emailRegex;
		
		function __construct() {
			$this->emailRegex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		}
	
		function post() {
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			if (preg_match($this->emailRegex, $email)) {
				 if (!empty($password)) {
					$login = new PSNLogIn();

					$login->setEmail($email);
					$login->setPassword($password);
					
					echo $login->login();
			
				 } else {
					echo 'Please enter your password!';
				 }
				 
			} else { 
				 echo 'Please enter a valid e-Mail!';
			} 
		}
	}
?>