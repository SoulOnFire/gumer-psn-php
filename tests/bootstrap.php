<?php

if (! file_exists('../vendor/autoload.php'))
{
	echo 'You must run `composer install` before running tests.';
	exit;
}

require_once '../vendor/autoload.php';

 // @todo: Set Guzzle plugin for mock.