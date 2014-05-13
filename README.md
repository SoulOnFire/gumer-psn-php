gumer-psn-php
=============

A Playstation Network API written in PHP based on gumer-psn by José A. Sächs ([github.com/jhewt/gumer-psn](https://www.github.com/jhewt/gumer-psn))

This port also uses Toro ([github.com/anandkunal/ToroPHP](https://www.github.com/anandkunal/ToroPHP)) as I am a minimalist and want work to be done.

Try it out here: http://ilendemli.info/PSN/index.php/

**Changelogs:**
* **v0.2**:
	* updated for the new login procedure by sony
	* make the code a bit more tidier

* **v0.1**:
	* initial release

##About
This script is based on gumer-psn which is developed in Node.js.
@jhewt decompiled Sony's official Android application and found methods that receives JSON data instead of XML or other types.

For now this script can do:
* Log in to the site and receive access and refresh tokens
* Refresh the access token
* Get profile data
* Get friend list (you always can get your friendlist, you can also get the friendlist of a friend of yours, they just have to allow it)
* Get trophies
* Get all coversations (as overview)
* Get one conversation (as chat, gets all messages)
* Send a text message

##Features planned
* Friends
	* Friends management (add, delete, block)
	* Messaging with voice and image
* Profile feeds (depends on users privacy)
* Notifications

##Requirements
* A valid PSN account
* A webserver (can be locally or hosted somewhere)
* Some brain

##Installing
I don't have to explain this, do I? But let me just say that you need a folder called 'tmp' in the same folder where the login.php is

##Usage

You can directly call the PHP functions if you know what you do, or use JavaScript to call the Toro links and exchange data (I mainly used POST as the method)

###PHP
```php
// Login
$login = new PSNLogIn();

$login->setEmail($email); // Type in your email here
$login->setPassword($password); // Type in your password here

$tokens = $login->login(); // Returns an array with the access and refresh tokens only if the login was succeed

// Get my profile informations
$parser = new PSNParser($region, $language); // Set your region and language f.e. PSNParser('at', 'de')

echo $parser->getMyInfos($accessToken); // Where $accessToken = $tokens['access_token'], returns your profile informations as JSON
```

###JavaScript
```javascript
$.post("/login", { 
	'email': email,
	'password': password
	
}).done(function(data) {
	alert(data);
});
```

```javascript
$.post("/me", { 
	'accessToken': accessToken,
	'region': region,
	'language': language
	
}).done(function(data) {
	alert(data);
});
```

and so on..

Contribute
==========

NOTE:
	I do own a PlayStation 3 system, so i can spam my friends with messages and check if they are online.
	Those features are within PSN's app but I do not have an Android device (my parent have one though),
	I downloaded the PSN app on my iPhone 5 and the apps are similar. I can sniff the data which 
	are send to PlayStation servers, so I was able to create the code to send messages.

	You can donate to ilendemli@live.at, if you want to support me and my work. Any support is appreciated!


