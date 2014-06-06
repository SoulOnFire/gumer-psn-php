<?php

if (! file_exists(__DIR__ . '/../vendor/autoload.php'))
{
	echo 'You must run `composer install` before running tests.';
	exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

 // @todo: Set Guzzle plugin for mock.