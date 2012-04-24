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
	protected static $defaultAction = 'view';
	
	
	// ---
	// Constructor
	// ---
	
	public function __construct($id = '')
	{
		$this->id = $id;
	}
	
	
	// ---
	// Page contents
	// ---
	
	public function viewPage()
	{
		$contents = $this->view();
		
		$mainFile = __DIR__ . '/../Views/Main.php';
		$output = Template::parse($mainFile, array(
			'title' =>  $this->getTitle(),
			'contents' => $contents
		));
		
		return $output;
	}
	
	public function getRequiredAuthorizationLevel()
	{
		return 0;
	}
	
	public abstract function getTitle();
	
	
	// ---
	// Perform arbitrary action
	// ---
	
	protected function getAllowedActions()
	{
		$actions['view'] = $this->getRequiredAuthorizationLevel();
		
		return $actions;
	}
	
	public final function performAction($action, $authorizationLevel = 0, $onlyContents = false)
	{
		if (empty($action))
			$action = self::$defaultAction;
		
		$allowedActions = $this->getAllowedActions();
		if (array_key_exists($action, $allowedActions))
		{
			$requiredAuthorizationLevel = $allowedActions[$action];
			if ($authorizationLevel >= $requiredAuthorizationLevel)
			{
				if (method_exists($this, $action))
				{
					if ($action != self::$defaultAction || $onlyContents)
					{
						$output = $this->{$action}();
					}
					
					if (!$onlyContents)
					{
						$output = $this->viewPage();
					}
					
					print($output);
				}
			}
		}
	}
	
	
	// ---
	// Actions
	// ---
	
	public function view()
	{
		return Template::parseTemplateInterface($this);
	}
}