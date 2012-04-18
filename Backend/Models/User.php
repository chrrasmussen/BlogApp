<?php

require_once(__DIR__ . '/../App.php');
require_once(__DIR__ . '/AbstractModel.php');


/**
 * User model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class User extends AbstractModel
{
	protected $userId;
	protected $fullName;
	protected $email;
	protected $password;
	protected $authorizationLevel;
	
	
	// ---
	// Creating User instances
	// ---
	
	public static function getUserForId($userId)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT userId, fullName, email, password, authorizationLevel FROM users WHERE userId = '%s'",
			$db->real_escape_string($userId)
		);
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			return $result->fetch_object(__CLASS__);
		}
	}
	
	public static function getUserByEmailAndPassword($email, $password)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT userId, fullName, email, password, authorizationLevel FROM users WHERE email = '%s' AND password = '%s'",
			$db->real_escape_string($email),
			$db->real_escape_string($password)
		);
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			return $result->fetch_object(__CLASS__);
		}
	}
	
	
	// Accessors
	
	public function getUserId()
	{
		return intval($this->userId);
	}
	
	
	// ---
	// AbstractModel methods
	// ---
	
	protected function getModelAttributes()
	{
		return array(
			'userId' => 'integer',
			'fullName' => 'string',
			'email' => 'string',
			'password' => 'string',
			'authorizationLevel' => 'integer'
		);
	}
	
	public function delete()
	{
		$db = App::getDB();
		
		$query = sprintf("DELETE FROM users WHERE userId = '%s'",
			$db->real_escape_string($this->getUserId())
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			$this->setUserId(0);
			return true;
		}
	}
	
	protected function insert()
	{
		$db = App::getDB();
		
		$query = sprintf("INSERT INTO users (fullName, email, password, authorizationLevel) VALUES ('%s', '%s', '%s', '%s')",
			$db->real_escape_string($this->getFullName()),
			$db->real_escape_string($this->getEmail()),
			$db->real_escape_string($this->getPassword()),
			$db->real_escape_string($this->getAuthorizationLevel())
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			$this->setUserId($db->insert_id);
			return true;
		}
	}
	
	protected function update()
	{
		$db = App::getDB();
		
		$query = sprintf("UPDATE users SET fullName = '%s', email = '%s', password = '%s', authorizationLevel = '%s' WHERE userId = '%s'",
			$db->real_escape_string($this->getFullName()),
			$db->real_escape_string($this->getEmail()),
			$db->real_escape_string($this->getPassword()),
			$db->real_escape_string($this->getAuthorizationLevel()),
			$db->real_escape_string($this->getUserId())
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			return true;
		}
	}
	
	public function isPersisted()
	{
		return ($this->getUserId() > 0);
	}
}