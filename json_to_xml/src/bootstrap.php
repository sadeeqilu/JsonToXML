<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();


// run $ php bootstrap.php to test env
echo getenv('URL');