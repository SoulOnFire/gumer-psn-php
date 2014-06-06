<?php

require_once '../vendor/autoload.php';


// Setup the connections and instances.
$client     = new Guzzle\Http\Client('', ['redirect.disable' => true]);
$connection = new Gumer\PSN\Http\Connection;
$connection->setGuzzle($client);
$provider   = new Gumer\PSN\Authentication\UserProvider($connection);
$auth       = Gumer\PSN\Authentication\Manager::instance($provider);

// Attempt to login.
$auth->attempt('username_here', 'password_here');


// Get the current user profile.
$request    = new Gumer\PSN\Requests\GetMyInfoRequest;
$response   = $connection->call($request);
$info       = json_decode($response->getBody(true), true);

// Get the friends list for the current 
$request    = new Gumer\PSN\Requests\TrophyDataRequest;
$request->setUserId('LoVeRSaMa');

dd($request->getUri());

$response   = $connection->call($request);

dd($response->getBody(true));