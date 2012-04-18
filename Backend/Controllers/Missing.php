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
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$actions = parent::getAllowedActions();
		
		return $actions;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Pages/Missing.php';
	}
}