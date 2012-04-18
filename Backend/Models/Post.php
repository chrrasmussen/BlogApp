<?php

require_once(__DIR__ . '/../App.php');
require_once(__DIR__ . '/../Libraries/Template/TemplateInterface.php');
require_once(__DIR__ . '/AbstractModel.php');
require_once(__DIR__ . '/User.php');


/**
 * Post model
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Post extends AbstractModel implements TemplateInterface
{
	protected $postId;
	protected $userId;
	protected $title;
	protected $body;
	protected $createdAt;
	protected $modifiedAt;
	
	protected $user;
	
	
	// ---
	// Creating Post instances
	// ---
	
	public static function getPostByURL($postURL)
	{
		$id = self::getIdFromPostURL($postURL);
		return self::getPostForId($id);
	}
	
	public static function getPostForId($postId)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT postId, userId, title, body, createdAt, modifiedAt FROM posts WHERE postId = '%s'",
			$db->real_escape_string($postId)
		);
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			return $result->fetch_object(__CLASS__);
		}
	}
	
	public static function getPosts($limit = 10, $offset = 0)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT postId, userId, title, body, createdAt, modifiedAt FROM posts ORDER BY createdAt DESC LIMIT %u,%u",
			$db->real_escape_string($offset),
			$db->real_escape_string($limit)
		);
		
		$posts = array();
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			while ($post = $result->fetch_object(__CLASS__))
			{
				array_push($posts, $post);
			}
		}
		
		return $posts;
	}
	
	public static function getPostsForSearchQuery($searchQuery = '', $limit = 10)
	{
		$db = App::getDB();
		
		$query = sprintf("SELECT postId, userId, title, body, createdAt, modifiedAt FROM posts WHERE title LIKE '%%%s%%' OR body LIKE '%%%s%%' ORDER BY createdAt DESC LIMIT %u",
			$db->real_escape_string($searchQuery),
			$db->real_escape_string($searchQuery),
			$db->real_escape_string($limit)
		);
		
		$posts = array();
		
		if (($result = $db->query($query)) && ($result->num_rows > 0))
		{
			while ($post = $result->fetch_object(__CLASS__))
			{
				array_push($posts, $post);
			}
		}
		
		return $posts;
	}
	
	public static function getNoPostsContents()
	{
		return Template::parse(__DIR__ . '/../Views/Snippets/NoPosts.php');
	}
	
	public static function deletePostForId($postId)
	{
		$db = App::getDB();
		
		$query = sprintf("DELETE FROM posts WHERE postId = '%s'",
			$db->real_escape_string($postId)
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			return true;
		}
	}
	
	
	// ---
	// Accessors
	// ---
	
	public function setBody($value)
	{
		$trimmedValue = trim($value);
		$strippedValue = strip_tags($trimmedValue, '<h3><p><b><i><u>');
		$this->body = $strippedValue;
	}
	
	public function getPostId()
	{
		return intval($this->postId);
	}
	
	public function getUserId()
	{
		return intval($this->userId);
	}
	
	public function getPostURL()
	{
		$cleanTitle = preg_replace('/[^\w]+/', '-', $this->getTitle());
		$trimmedCleanTitle = trim($cleanTitle, '-');
		return sprintf("%s-%d", $trimmedCleanTitle, $this->getPostId());
	}
	
	public static function getIdFromPostURL($postURL)
	{
 		preg_match('/.*-(\d+)$/', $postURL, $matches);
 		if (count($matches) >= 2)
			return intval($matches[1]);
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
			'postId' => 'integer',
			'userId' => 'integer',
			'title' => 'string',
			'body' => 'string',
			'createdAt' => 'string',
			'modifiedAt' => 'string'
		);
	}
	
	public function delete()
	{
		if (self::deletePostForId($this->getPostId()))
		{
			$this->setPostId(0);
			return true;
		}
	}
	
	protected function insert()
	{
		$db = App::getDB();
		
		$query = sprintf("INSERT INTO posts (userId, title, body, createdAt) VALUES ('%s', '%s', '%s', NOW())",
			$db->real_escape_string($this->getUserId()),
			$db->real_escape_string($this->getTitle()),
			$db->real_escape_string($this->getBody())
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			$this->setPostId($db->insert_id);
			$this->setModifiedAt('Y-m-d H:i:s');
			return true;
		}
	}
	
	protected function update()
	{
		$db = App::getDB();
		
		$query = sprintf("UPDATE posts SET userId = '%s', title = '%s', body = '%s' WHERE postId = '%s'",
			$db->real_escape_string($this->getUserId()),
			$db->real_escape_string($this->getTitle()),
			$db->real_escape_string($this->getBody()),
			$db->real_escape_string($this->getPostId())
		);
		
		if (($db->query($query)) && ($db->affected_rows > 0))
		{
			return true;
		}
	}
	
	public function isPersisted()
	{
		return ($this->getPostId() > 0);
	}
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['postId'] = $this->getPostId();
		$values['fullName'] = $this->getUser()->getFullName();
		$values['title'] =  $this->getTitle();
		$values['body'] =  $this->getBody();
		$values['createdAt'] =  $this->getCreatedAt();
		$values['modifiedAt'] =  $this->getModifiedAt();
		
		$values['postURL'] = $this->getPostURL();
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Snippets/SinglePost.php';
	}
	
	public function __toString()
	{
		return Template::parseTemplateInterface($this);
	}
}