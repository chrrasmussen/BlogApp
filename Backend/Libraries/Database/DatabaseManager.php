<?php

/**
 * Database manager singleton
 *
 * @author zombat (http://stackoverflow.com/questions/1898762/php-dealing-with-mysql-connection-variable)
 * @auther Christian Rasmussen <christian.rasmussen@me.com>
 */
class DatabaseManager
{
	private static $instance;
	private $dbConnection; 
	
	public static function getInstance()
	{
		if (self::$instance == null)
		{
			$className = __CLASS__;
			self::$instance = new $className();
		}
		
		return self::$instance;
	}
	
	public static function getDB()
	{
		return self::getInstance()->dbConnection;
	}
	
	public static function setDB(mysqli $dbConnection)
	{
		self::getInstance()->dbConnection = $dbConnection;
	}
}