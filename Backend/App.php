<?php

require_once(__DIR__ . '/EnvironmentVariables.php');
require_once(__DIR__ . '/PageFactory.php');

require_once(__DIR__ . '/Libraries/Template/Template.php');
require_once(__DIR__ . '/Libraries/Cryptography/Bcrypt.php');
require_once(__DIR__ . '/Models/User.php');


/**
 * Application singleton
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class App
{
	private static $db;
	
	private static $page;
	private static $id;
	private static $action;
	private static $onlyContents;
	
	
	public static function run()
	{
		session_start();
		
		self::getURLParameters();
		self::setUpDB();
		$isLoggedIn = self::authenticate();
		if (empty($isLoggedIn))
			self::loadPage();
	}
	
	// ---
	// Accessors
	// ---
	
	public static function getDB()
	{
		return self::$db;
	}
	
	public static function getBaseURL()
	{
		return $_ENV['app']['baseURL'];
	}
	
	public static function getPage()
	{
		return self::$page;
	}
	
	public static function getId()
	{
		return self::$id;
	}
	
	public static function getAction()
	{
		return self::$action;
	}
	
	public static function getOnlyContents()
	{
		return self::$onlyContents;
	}
	
	public static function isLoggedIn()
	{
		return isset($_SESSION['user']);
	}
	
	public static function getUser()
	{
		if (self::isLoggedIn())
			return $_SESSION['user'];
	}
	
	public static function getLoginToolbar()
	{
		if (self::isLoggedIn())
			print(self::getLoginToolbarLogOutContents());
		else
			print(self::getLoginToolbarLogInContents());
	}
	
	
	// ---
	// Helper methods
	// ---
	
	public static function concatenatePageURL($baseURL, $page = '', $id = '', $queryParameters = array())
	{
		$pageURL = $baseURL . '/index.php';
		
		if (!empty($page))
		{
			$pageURL .= '?page=' . $page;
			
			if (!empty($id))
				$pageURL .= '&id=' . $id;
		}
		
		if (!empty($queryParameters))
		{
			$separator = (!empty($page)) ? '&' : '?';
			$queryString = http_build_query($queryParameters);
			$pageURL .= $separator . $queryString;
		}
		
		return $pageURL;
	}
	
	private static function getLoginToolbarLogInContents()
	{
		$values = array(
			'pageURL' => App::concatenatePageURL(App::getBaseURL(), App::getPage(), App::getId(), array('action' => 'logIn'))
	    );
	    return Template::parse(__DIR__ . '/Views/Snippets/LoginToolbarLogIn.php', $values);
	}
	
	private static function getLoginToolbarLogOutContents()
	{
		$values = array(
		    'pageURL' => App::concatenatePageURL(App::getBaseURL(), App::getPage(), App::getId(), array('action' => 'logOut')),
		    'fullName' => App::getUser()->getFullName()
		);
		return Template::parse(__DIR__ . '/Views/Snippets/LoginToolbarLogOut.php', $values);
	}
	
	
	// ---
	// Main methods
	// ---
	
	private static function getURLParameters()
	{
		self::$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
		self::$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
		self::$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
		
		self::$onlyContents = isset($_GET['onlyContents']);
	}
	
	public static function setUpDB()
	{
		$mc = $_ENV['mysql'];
		$db = new mysqli($mc['host'], $mc['username'], $mc['password'], $mc['database'], $mc['port']);
		if (!$db)
			die('Failed to connect to database');
		$db->set_charset('utf8');
		
		self::$db = $db;
	}
	
	private static function authenticate()
	{
		if  (self::getAction() == 'logIn')
		{
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
			$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
			
/*
			$bcrypt = new Bcrypt(7, 'blogapp');
			$hashedPassword = $bcrypt->hash($password);
*/
			
			$user = User::getUserByEmailAndPassword($email, $password);
			$isAuthenticated = !empty($user);
			if ($isAuthenticated)
			{
				$_SESSION['user'] = $user;
				
				if (self::getOnlyContents())
				{
					print(self::getLoginToolbarLogOutContents());
					return true;
				}
			}
		}
		else if (self::getAction() == 'logOut')
		{
			unset($_SESSION['user']);
			session_destroy();
			
			if (self::getOnlyContents())
			{
				print(self::getLoginToolbarLogInContents());
				return false;
			}
		}
	}
	
	private static function loadPage()
	{
		$authorizationLevel = (self::isLoggedIn()) ? 10 : 0;
		
		$controller = PageFactory::load(self::getPage(), self::getId(), $authorizationLevel);
		$controller->performAction(App::getAction(), $authorizationLevel, self::getOnlyContents());
	}
}