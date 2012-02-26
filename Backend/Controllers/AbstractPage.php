<?php

require_once(__DIR__ . '/../Libraries/Template/Template.php');


/**
 * Base page controller
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
abstract class AbstractPage implements TemplateInterface
{
	protected $id;
	
	
	// ---
	// Constructor
	// ---
	
	public function __construct($id = '')
	{
		$this->id = $id;
	}
	
	
	// ---
	// Display page
	// ---
	
	public function display()
	{
		$contents = Template::parseTemplateInterface($this);
		print($contents);
	}
	
	
	// ---
	// Page contents
	// ---
	
	public function getRequiredAuthorizationLevel()
	{
		return 0;
	}
	
	public abstract function getTitle();
	
/* 	public abstract function getToolbar() */
	
	public abstract function getContentsFile();
	
	
	// ---
	// Perform arbitrary action
	// ---
	
	protected function getAllowedActions()
	{
		return array();
	}
	
	public final function performAction($action, $authorizationLevel = 0)
	{
		$allowedActions = $this->getAllowedActions();
		if (array_key_exists($action, $allowedActions))
		{
			$requiredAuthorizationLevel = $allowedActions[$action];
			if ($authorizationLevel >= $requiredAuthorizationLevel)
			{
				if (method_exists($this, $action))
					$this->{$action}();
			}
		}
	}
	
	
	// ---
	// TemplateInterface methods
	// ---
	
	public function getTemplateValues()
	{
		$values['title'] =  $this->getTitle();
		$values['contentsFile'] = $this->getContentsFile();
		
		return $values;
	}
	
	public function getTemplateFile()
	{
		return __DIR__ . '/../Views/Main.php';
	}
}