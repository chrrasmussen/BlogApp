<?php

require_once(__DIR__ . '/AbstractPage.php');


/**
 * Error controller
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Missing extends AbstractPage
{
	public function getTitle()
	{
		return 'Missing';
	}
	
	public function getContentsFile()
	{
		return __DIR__ . '/../Views/Pages/Missing.php';
	}
}