<?php

require_once(__DIR__ . '/AbstractPage.php');
require_once(__DIR__ . '/../Models/Post.php');


/**
 * Posts controller
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Posts extends AbstractPage
{
	public function getTitle()
	{
		return 'Posts';
	}
	
	public function getContentsFile()
	{
		return __DIR__ . '/../Views/Pages/Posts.php';
	}
	
	public function getTemplateValues()
	{
		$values = parent::getTemplateValues();
		$values['posts'] = $this->getPosts();
		
		return $values;
	}
	
	
	// ---
	// Get values
	// ---
	
	public function getPosts()
	{
		$posts = array(
			Template::parse(__DIR__ . '/../Views/Snippets/SinglePost.php', array(
				'title' => 'Post 2',
				'name' => 'Christian Rasmussen',
				'date' => '2012-01-27',
				'body' => 'This is my second post!'
			)),
			
			Template::parse(__DIR__ . '/../Views/Snippets/SinglePost.php', array(
				'title' => 'Post 1',
				'name' => 'Christian Rasmussen',
				'date' => '2012-01-26',
				'body' => 'This is my first post!'
			))
		);
		
		return implode($posts);
	}
	
	
	// ---
	// Actions
	// ---
	
	protected function getAllowedActions()
	{
		return array(
			'test' => 11
		);
	}
	
	public function test()
	{
		print('It works!');
	}
}