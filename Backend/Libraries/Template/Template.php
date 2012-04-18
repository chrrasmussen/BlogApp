<?php

require_once(__DIR__ . '/TemplateInterface.php');


/**
 * A simple template engine
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Template
{
	public static function parse($templateFile, array $values = array())
	{
		// Decide path and validate
		if (is_readable($templateFile) === false)
		{
			throw new Exception('Missing file: ' . $templateFile);
			return false;
		}
		
		// Get contents from file using values from source
		extract($values);
		ob_start();
		include($templateFile);
		$output = ob_get_clean();
		
		// Return contents
		return $output;
	}
	
	public static function parseTemplateInterface(TemplateInterface $source, array $additionalValues = array())
	{
		$values = array_merge($source->getTemplateValues(), $additionalValues);
		return self::parse($source->getTemplateFile(), $values);
	}
}