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
	public function getTitle()
	{
		return 'PostDetails: ' . $this->id;
	}
	
	public function getContentsFile()
	{
		return __DIR__ . '/../Views/Pages/PostDetails.php';
	}
	
	public function getTemplateValues()
	{
		$values = parent::getTemplateValues();
		$values['posts'] = 'Posts';
		$values['comments'] = 'Comments';
		
		return $values;
	}
	
	
	// ---
	// 
	// ---
	
	protected function getPostId()
	{
		
	}
	
	
	// ---
	// Actions
	// ---
}