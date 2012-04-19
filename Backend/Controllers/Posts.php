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
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['posts'] = $this->getPosts();
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Pages/Posts.php';
	}
	
	
	// ---
	// Get values
	// ---
	
	private function getPosts()
	{
		$searchQuery = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
/*
		$offset = filter_input(INPUT_GET, 'offset', FILTER_SANITIZE_STRING);
		$limit = filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_STRING);
*/
		
		if (!empty($searchQuery))
			$posts = Post::getPostsForSearchQuery($searchQuery, 10);
		else
			$posts = Post::getPosts(5, 0);
		
		$output = '';
		foreach ($posts as $post)
		{
			$output .= (string)$post;
		}
		
		return $output;
	}
	
	
	// ---
	// Actions
	// ---
	
	protected function getAllowedActions()
	{
		$actions = parent::getAllowedActions();
		
		return $actions;
	}
	
	public function getRecentPosts()
	{
		$posts = Post::getPosts(5, 0);
		
		$output = '';
		foreach ($posts as $post)
		{
			$values['title'] = '';
			$values['pageURL'] = '';
			$output .= Template::parse(__DIR__ . '/../Views/Snippets/SidebarRecentPost.php', $values);
		}
		
		return $output;
	}
	
	public function getRecentComments()
	{
	
	}
}