<?php

require_once(__DIR__ . '/TemplateInterface.php');


/**
 * A simple template engine
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Template
{
	public static function parse($templateFile, array $values)
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
	
	public static function parseTemplateInterface(TemplateInterface $source)
	{
		return self::parse($source->getTemplateFile(), $source->getTemplateValues());
	}
}