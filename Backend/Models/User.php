<?php

require_once(__DIR__ . '/AbstractModel.php');
/* require_once(__DIR__ . '/../Libraries/Cryptography/Bcrypt.php'); */


/**
 * User model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class User extends AbstractModel
{
	protected $userId;
	protected $username;
	protected $password;
	
	protected function getModelAttributes()
	{
		return array(
			'userId' => 'integer',
			'username' => 'string',
			'password' => 'string'
		);
	}
	
	public function __construct($username, $password)
	{
		$this->setUsername($username);
		$this->setPassword($password);
	}
	
	public function save()
	{
		if (!$this->isPersisted())
			return $this->insert();
		else
			return $this->update();
	}
	
	public function delete()
	{
		$db = DatabaseManager::getDB();
		$query = sprintf("DELETE FROM users WHERE userId = '%s'",
			$db->real_escape_string($this->getUserId())
		);
		if ($db->query($query))
		{
			return true;
		}
	}
	
	private function insert()
	{
		$db = DatabaseManager::getDB();
		$query = sprintf("INSERT INTO users (username, password) VALUES (%s, %s)",
			$db->real_escape_string($this->getUsername()),
			$db->real_escape_string($this->getPassword())
		);
		if ($db->query($query))
		{
			$userId = $db->insert_id();
			return true;
		}
	}
	
	private function update()
	{
		$db = DatabaseManager::getDB();
		$query = sprintf("UPDATE users SET username = '%s', password = '%s' WHERE userId = '%s'",
			$db->real_escape_string($this->getUsername()),
			$db->real_escape_string($this->getPassword()),
			$db->real_escape_string($this->getUserId())
		);
		if ($db->query($query)) // TODO: Check mysql_affected_rows() instead?
		{
			return true;
		}
	}
	
	public function isPersisted()
	{
		return ($userId > 0);
	}
	
	
	public static function addUser($username, $password)
	{
		$user = new self($username, $password);
		return $user->insert();
	}
	
	public static function getUserById($userId)
	{
		$db = DatabaseManager::getDB();
		$query = sprintf("SELECT * FROM users WHERE userId = '%s'",
			$db->real_escape_string($userId)
		);
		if ($result = $db->query($query))
		{
			return $result->fetch_object(__CLASS__);
		}
	}
}