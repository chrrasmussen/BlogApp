<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');


// Get parameters from URL
$page   = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
$id     = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);


// Load configurations
require_once(__DIR__ . '/../Backend/Config.php');


// Set up database connection
require_once(__DIR__ . '/../Backend/Libraries/Database/DatabaseManager.php');
$mc = $_ENV['mysql'];
$dbConnection = new mysqli($mc['host'], $mc['username'], $mc['password'], $mc['database'], $mc['port']);
if (!$dbConnection)
	die('Failed to connect to database');
DatabaseManager::setDB($dbConnection);


// Authenticate user
/* require_once(__DIR__ . '/../Backend/Libraries/Authentication/Authentication.php'); */
$authorizationLevel = 10;


// Load page
require_once(__DIR__ . '/../Backend/PageFactory.php');
$controller = PageFactory::load($page, $id, $authorizationLevel);


// Perform action
$controller->performAction($action, $authorizationLevel);
// TODO: Hvordan skal funksjonen få tildelt parametere? filter_input inni metoden
// TODO: Alternativt kan actions komme fra WebSocket


// Show page
$controller->display();


/*
require_once(__DIR__ . '/../Backend/Models/User.php');
print_r(User::getUserById("s"));
*/

require_once(__DIR__ . '/../Backend/Models/User.php');


/*
$abc = 5;
unset($abc);
print(isset($abc));
*/
/* $user = new User('a'); */
/* print("$user->username + $user->password"); */
/* $user->userId = "s"; */
$user = User::getUserById(5);