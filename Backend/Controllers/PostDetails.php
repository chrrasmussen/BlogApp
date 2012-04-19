<?php

require_once(__DIR__ . '/AbstractPage.php');
require_once(__DIR__ . '/../Models/Post.php');
require_once(__DIR__ . '/../Models/Comment.php');


/**
 * Post details controller
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class PostDetails extends AbstractPage
{
	private $post;
	
	public function __construct($id = '')
	{
		parent::__construct($id);
		
		$this->post = Post::getPostByURL($this->id);
	}
	
	public function getTitle()
	{
		if (empty($this->post))
			return 'PostDetails';
		
		return 'PostDetails: ' . $this->post->title;
	}
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['posts'] = $this->getPost();
		$values['comments'] = $this->getComments();
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Pages/PostDetails.php';
	}
	
	
	// ---
	// Get values
	// ---
	
	protected function getComments()
	{
		$postId = Post::getIdFromPostURL($this->id);
		$comments = Comment::getCommentsForPostId($postId);
		
		$output = '';
		foreach ($comments as $comment)
		{
			$output .= (string)$comment;
		}
		
		return $output;
	}
	
	
	// ---
	// Actions
	// ---
	
	protected function getAllowedActions()
	{
		$actions = parent::getAllowedActions();
		$actions['getPost'] = 0;
		$actions['addPost'] = 10;
		$actions['updatePost'] = 10;
		$actions['deletePost'] = 10;
		$actions['addComment'] = 0;
		$actions['deleteComment'] = 10;
		
		return $actions;
	}
	
	protected function getPost()
	{
		if (empty($this->post))
			return '';
		
		$output = (string)$this->post;
		
		return $output;
	}
	
	public function addPost()
	{
		$postId = Post::getIdFromPostURL($this->id);
		$userId = App::getUser()->getUserId();
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$body = filter_input(INPUT_POST, 'body', FILTER_DEFAULT, array(FILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP));
		
		if (empty($title) || empty($body))
			return;
		
		$post = new Post();
		$post->postId = $postId;
		$post->userId = $userId;
		$post->title = $title;
		$post->body = $body;
		$post->save();
		
		return (string)$post;
	}
	
	public function updatePost()
	{
		if (empty($this->post))
			return '';
		
		$userId = App::getUser()->getUserId();
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$body = filter_input(INPUT_POST, 'body', FILTER_DEFAULT, array(FILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP));
		
		if (empty($title) || empty($body))
			return;
		
		$this->post->userId = $userId;
		$this->post->title = $title;
		$this->post->body = $body;
		$this->post->save();
		
		return (string)$this->post;
	}
	
	public function deletePost()
	{
		$postId = Post::getIdFromPostURL($this->id);
		
		if (Post::deletePostForId($postId))
		{
			return 'Deleted';
		}
	}
	
	public function addComment()
	{
		$postId = Post::getIdFromPostURL($this->id);
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);
		
		// Validate
		if ((empty($name) && !App::isLoggedIn()) || empty($body))
			return;
		
		$comment = new Comment();
		$comment->postId = $postId;
		$comment->body = $body;
		
		// Set name
		if (App::isLoggedIn())
		{
			$comment->userId = App::getUser()->userId;
			$comment->name = App::getUser()->fullName; // In case the user gets deleted
		}
		else
		{
			$comment->name = $name;
		}
		
		$comment->save();
		
		return (string)$comment;
	}
	
	public function deleteComment()
	{
		$commentId = filter_input(INPUT_POST, 'commentId', FILTER_SANITIZE_STRING);
		
		if (Comment::deleteCommentForId($commentId))
		{
			return 'Deleted';
		}
	}
}