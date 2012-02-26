<?php

require_once(__DIR__ . '/../Libraries/Template/TemplateInterface.php');
require_once(__DIR__ . '/Post.php');
require_once(__DIR__ . '/User.php');


/**
 * Comment model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Comment implements TemplateInterface
{
	public $commentId;
	public $postId;
	public $userId;
	public $name;
	public $body;
	public $createdAt;
	
	public function addComment($userId, $name, $body)
	{
		if ( !(is_int($userId) && is_string($name) && is_string($body)) )
			throw new InvalidArgumentException();
		
/*
		$query = "INSERT INTO comments (user_id, name, body) VALUES ($userId, '$name', '$body')";
		$m = new mysqli();
		$m->query($query);
*/
	}
	
	public function deleteComment($commentId)
	{
		if ( !(is_int($commentId)) )
			return;
		
		
	}
	
	public function getComment($commentId)
	{
		if ( !(is_int($commentId)) )
			return;
		
		
	}
	
	public function getCommentsForPost($postId)
	{
		if ( !(is_int($postId)) )
			return;
		
		
	}
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['name'] = $this->name;
		$values['date'] = $this->createdAt;
		$values['body'] = $this->body;
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Snippets/SingleComment.php';
	}
}