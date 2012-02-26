<?php

require_once(__DIR__ . '/../Libraries/Template/TemplateInterface.php');
require_once(__DIR__ . '/User.php');


/**
 * Post model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Post implements TemplateInterface
{
	public $postId;
	public $userId;
	public $name;
	public $title;
	public $body;
	public $createdAt;
	
	public static function addPost($userId, $title, $body)
	{
		
	}
	
	public static function editPost($postId, $userID, $title, $body)
	{
		
	}
	
	public static function deletePost($postId)
	{
		
	}
	
	public static function getPost($postId)
	{
		
	}
	
	public static function getPosts($limit, $offset)
	{
		// TODO: Sorting? Ascending/descending
	}
	
	
	public static function getPostById($postId)
	{
		if ( !(is_int($postId)) )
			throw new InvalidArgumentException();
		
		$db = DatabaseManager::getDB();
		$query = sprintf("SELECT * FROM users WHERE postId = '%s'", mysql_real_escape_string($postId));
		if ($result = $db->query($query))
			return $result->fetch_object(__CLASS__);
	}
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['name'] = $this->name;
		$values['date'] =  $this->createdAt;
		$values['title'] =  $this->title;
		$values['body'] =  $this->body;
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Snippets/SinglePost.php';
	}
}