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
		'/login/refresh' => 'Refresh',
		'/me' => 'Me',
		'/profile' => 'Profile',
		'/profile/state' => 'State',
		'/friends' => 'Friends',
		'/trophies' => 'Trophies',
		'/trophies/game' => 'GameTrophies',
		'/trophies/game/trophy' => 'TrophyById',
		'/conversations' => 'Conversations',
		'/conversations/chat' => 'Chat',
		'/conversations/chat/message' => 'Message'
	));
	
	class State {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			
			$parser = new PSNParser($region, $language);
			
			echo $parser->getState($accessToken, $profileId);
		}
	}
	
	class TrophyById {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			$gameId = $_POST['gameId'];
			$trophyId = $_POST['trophyId'];
			
			$parser = new PSNParser($region, $language);
	
			echo $parser->getTrophyById($accessToken, $profileId, $gameId, $trophyId);
		}
	}
	
	class GameTrophies {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
			$gameId = $_POST['gameId'];
			
			$parser = new PSNParser($region, $language);
	
			echo $parser->getGameTrophies($accessToken, $profileId, $gameId);
		}
	}
	
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
	
	class Conversations {
		function post() {
			$accessToken = $_POST['accessToken'];
			$profileId = $_POST['profileId'];
			$region = $_POST['region'];
			$language = $_POST['language'];
	
			$parser = new PSNParser($region, $language);
	
			echo $parser->getConversations($accessToken, $profileId);
		}
	}
			
	class Trophies {
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
		function post() {
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
		
		function get() {
			?>
			<form action="./login" method="post">
				<input type="email" name="email" placeholder="e-Mail" />
				<input type="password" name="password" placeholder="Password" />
				<input type="submit" value="LogIn" />
			</form>
			<?php
		}
		
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
