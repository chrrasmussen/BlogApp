<?php

/**
 * Page factory
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class PageFactory
{
	protected static $allowed = array('Posts', 'PostDetails');
	protected static $default = 'Posts';
	protected static $missing = 'Missing';
	
	public static function load($page, $id = '', $authorizationLevel = 0)
	{
		// Check if string is empty
		if (empty($page))
			return self::createController(self::$default);
		
		// Check if controller is allowed
		if (!in_array($page, self::$allowed, true))
			return self::createController(self::$missing);
		
		// Check authorization level
		$controller = self::createController($page, $id);
		if (!(is_int($authorizationLevel) && $authorizationLevel >= $controller->getRequiredAuthorizationLevel()))
			return self::createController(self::$missing);
		
		return $controller;
	}
	
	protected static function createController($page, $id = '')
	{
		$path = __DIR__ . "/Controllers/{$page}.php";
		require_once($path);
		$reflector = new ReflectionClass($page);
		return $reflector->newInstanceArgs(array($id));
	}
}