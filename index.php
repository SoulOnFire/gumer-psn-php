<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	session_start();
	
	require_once('login.php');
	require_once('parser.php');
	
	if (isset($_POST['refresh'])) {
		unset($_SESSION['tokens']);
		header("Location: index.php");
	
	} else if (isset($_POST['login'])) {
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['password'] = $_POST['password'];
		unset($_SESSION['tokens']);
		header("Location: index.php");
		
	} else if (!isset($_SESSION['tokens'])) {
		if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
			$login = new PSNLogIn();

			$login->setEmail($_SESSION['email']);
			$login->setPassword($_SESSION['password']);
			
			$tokens = $login->login();
			$_SESSION['tokens'] = $tokens;
			
		} else {
			$tokens = -4;
		}
		
	} else {
		$tokens = $_SESSION['tokens'];
	}
	
	if(is_array($tokens)) {
		$accessToken = $tokens[0];
		$refreshToken = $tokens[1];
		
		if(empty($tokens[0]) || empty($tokens[1])) {
			$tokens = -5;
			
		} else {
			$parser = new PSNParser();
			$parser->setRegionAndLanguage('at', 'de');
			
			$myInfos = $parser->getMyInfos($accessToken);
			$myInfosAsArray = json_decode($myInfos, true);
			
			$myProfile = $parser->getProfile($accessToken, $myInfosAsArray['onlineId']);
			$myProfileAsArray = json_decode($myProfile, true);
			
			$friendlist = $parser->getFriendlist($accessToken, $myInfosAsArray['onlineId']);
			$friendlistAsArray = json_decode($friendlist, true);
			
			$friendProfile = $parser->getProfile($accessToken, 'JSachs13');
			$friendProfileAsArray = json_decode($friendProfile, true);
			
			$trophyData = $parser->getTrophies($accessToken, $myInfosAsArray['onlineId'], 'm', 0, 100);
			$trophyDataAsArray = json_decode($trophyData, true);
			
			$conversations = $parser->getConversations($accessToken, $myInfosAsArray['onlineId']);
			$conversationsAsArray = json_decode($conversations, true);
			
			/*
			$chat = $parser->getChat($accessToken, '##'); // ## must be the conversation id which can be found in $conversationAsArray
			$chatAsArray = json_decode($chat, true);
			
			// $parser->print_r($chatAsArray);
			$parser->sendMessage($accessToken, '##', 'TEST-MESSAGE: ' .date('H:i:s'));
			 */
		}
	} 
	
	if(!is_array($tokens)) {
		echo $tokens;
	}
?>
<br/>
<form action="index.php" method="POST">
	<input type="email" name="email" placeholder="e-Mail" />
	<input type="password" name="password" placeholder="Password" />
	<input type="submit" name="login" value="LogIn" />
	<input type="submit" name="refresh" value="Refresh" />
</form>