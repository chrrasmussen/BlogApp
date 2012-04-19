<?php

require_once(__DIR__ . '/../App.php');
require_once(__DIR__ . '/../Libraries/Template/TemplateInterface.php');
require_once(__DIR__ . '/AbstractModel.php');
require_once(__DIR__ . '/Post.php');
require_once(__DIR__ . '/User.php');


/**
 * Comment model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Comment extends AbstractModel implements TemplateInterface
{
	protected $commentId;
	protected $postId;
	protected $userId;
	protected $name;
	protected $body;
	protected $createdAt;
	
	protected $post;
	protected $user;
	
	
	// ---
	// Creating Comment instances
	// ---
	
	public static function getCommentForId($commentId)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT commentId, postId, userId, name, body, createdAt FROM comments WHERE commentId = '%s'",
			$db->real_escape_string($commentId)
		);
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			return $result->fetch_object(__CLASS__);
		}
	}
	
	public static function getCommentsForPostId($postId)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT commentId, postId, userId, name, body, createdAt FROM comments WHERE postId = '%s' ORDER BY createdAt ASC",
			$db->real_escape_string($postId)
		);
		
		$comments = array();
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			while ($comment = $result->fetch_object(__CLASS__))
			{
				array_push($comments, $comment);
			}
		}
		
		return $comments;
	}
	
	public static function getNoCommentsContents()
	{
		return Template::parse(__DIR__ . '/../Views/Snippets/NoComments.php');
	}
	
	public static function deleteCommentForId($commentId)
	{
		$db = App::getDB();
		
		$query = sprintf("DELETE FROM comments WHERE commentId = '%s'",
			$db->real_escape_string($commentId)
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			return true;
		}
	}
	
	
	// ---
	// Accessors
	// ---
	
	public function getCommentId()
	{
		return intval($this->commentId);
	}
	
	public function getPostId()
	{
		return intval($this->postId);
	}
	
	public function getUserId()
	{
		return intval($this->userId);
	}
	
	public function getName()
	{
		if ($this->getUserId() > 0 && $this->getUser() != null)
			return $this->getUser()->fullName;
		
		return $this->name;
	}
	
	public function getPost()
	{
		if (empty($this->post))
		{
			$this->post = Post::getPostForId($this->getPostId());
		}
		
		return $this->post;
	}
	
	public function getUser()
	{
		if (empty($this->user))
		{
			$this->user = User::getUserForId($this->getUserId());
		}
		
		return $this->user;
	}
	
	
	// ---
	// AbstractModel methods
	// ---
	
	protected function getModelAttributes()
	{
		return array(
			'commentId' => 'integer',
			'postId' => 'integer',
			'userId' => 'integer',
			'name' => 'string',
			'body' => 'string',
			'createdAt' => 'string'
		);
	}
	
	public function delete()
	{
		if (self::deleteCommentForId($this->getCommentId()))
		{
			$this->setCommentId(0);
			return true;
		}
	}
	
	protected function insert()
	{
		$db = App::getDB();
		
		if ($this->getUserId() > 0)
		{
			$query = sprintf("INSERT INTO comments (postId, userId, name, body, createdAt) VALUES ('%s', '%s', '%s', '%s', NOW())",
				$db->real_escape_string($this->getPostId()),
				$db->real_escape_string($this->getUserId()),
				$db->real_escape_string($this->getName()),
				$db->real_escape_string($this->getBody())
			);
		}
		else
		{
			$query = sprintf("INSERT INTO comments (postId, name, body, createdAt) VALUES ('%s', '%s', '%s', NOW())",
				$db->real_escape_string($this->getPostId()),
				$db->real_escape_string($this->getName()),
				$db->real_escape_string($this->getBody())
			);
		}
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			$this->setCommentId($db->insert_id);
			$this->setCreatedAt(date('Y-m-d H:i:s'));
			return true;
		}
	}
	
	protected function update()
	{
		$db = App::getDB();
		
		if ($this->getUserId() > 0)
		{
			$query = sprintf("UPDATE comments SET postId = '%s', userId = '%s', name = '%s', body = '%s' WHERE postId = '%s'",
				$db->real_escape_string($this->getPostId()),
				$db->real_escape_string($this->getUserId()),
				$db->real_escape_string($this->getName()),
				$db->real_escape_string($this->getBody()),
				$db->real_escape_string($this->getCommentId())
			);
		}
		else
		{
			$query = sprintf("UPDATE comments SET postId = '%s', name = '%s', body = '%s' WHERE postId = '%s'",
				$db->real_escape_string($this->getPostId()),
				$db->real_escape_string($this->getName()),
				$db->real_escape_string($this->getBody()),
				$db->real_escape_string($this->getCommentId())
			);
		}
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			return true;
		}
	}
	
	public function isPersisted()
	{
		return ($this->getCommentId() > 0);
	}
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['commentId'] = $this->getCommentId();
		$values['postId'] = $this->getPostId();
		$values['userId'] = $this->getUserId();
		$values['name'] = $this->getName();
		$values['createdAt'] = $this->getCreatedAt();
		$values['body'] = $this->getBody();
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Snippets/SingleComment.php';
	}
	
	public function __toString()
	{
		return Template::parseTemplateInterface($this);
	}
}