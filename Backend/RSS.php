<?php

require_once(__DIR__ . '/App.php');
require_once(__DIR__ . '/Libraries/Template/Template.php');
require_once(__DIR__ . '/Models/Post.php');


/**
 * RSS singleton
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class RSS
{
	public static function run()
	{
		App::setUpDB();
		
		self::loadRSS();
	}
	
	private static function loadRSS()
	{
		$values['posts'] = self::getPosts();
		$output = Template::parse(__DIR__ . '/Views/RSS/Main.php', $values);
		print($output);
	}
	
	private static function getPosts()
	{
		$posts = Post::getPosts(20, 0);
		
		$output = '';
		foreach ($posts as $post)
		{
			$link = App::concatenatePageURL(App::getBaseURL(), 'PostDetails', $post->getPostURL());
			$values['title'] = $post->title;
			$values['description'] = $post->body;
			$values['link'] = htmlentities($link, 0, 'UTF-8');
			$values['author'] = $post->getUser()->email;
			$values['pubDate'] = date(DATE_RSS, strtotime($post->createdAt));
			$output .= Template::parse(__DIR__ . '/Views/RSS/SinglePost.php', $values);
		}
		
		return $output;
	}
}