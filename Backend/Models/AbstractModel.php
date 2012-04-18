<?php

/**
 * Abstract model
 *
 * Subclasses will gain the following benefits:
 * - Dynamic accessors/mutators, i.e. $obj->setAttribute($value) is equal to $obj->attribute = $value
 * - The types of the input values are automatically validated
 * - Accessor/mutator implementations are called even if the attribute is accessed by property
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
abstract class AbstractModel
{
	// ---
	// CRUD
	// ---
	
	/**
	 * Save instance to data store
	 */
	public final function save()
	{
		if (!$this->isPersisted())
			return $this->insert();
		else
			return $this->update();
	}
	
	/**
	 * Delete instance from data store
	 */
	public abstract function delete();
	
	/**
	 * Insert instance into data store
	 */
	protected abstract function insert();
	
	/**
	 * Update instance in data store
	 */
	protected abstract function update();
	
	/**
	 * Check if instance is persisted in data store
	 */
	public abstract function isPersisted();
	
	
	// ---
	// Dynamic accessors/mutators
	// ---
	
	/**
	 * Gets a list of model attributes
	 * 
	 * Subclasses should override this method. The method returns an associative array where:
	 * - Keys represent the name of the attribute
	 * - Values represent the type of the attribute
	 */
	protected abstract function getModelAttributes();
	
	/**
	 * Gets the value of the model attribute
	 */
	public function __get($key)
	{
		if ($this->isModelAttribute($key))
		{
			// Call getter if it exists, otherwise get value from property
			$getter = sprintf('get%s', ucfirst($key));
			if (method_exists($this, $getter))
				return $this->$getter();
			else if (property_exists($this, $key))
				return $this->$key;
		}
	}
	
	/**
	 * Sets the value of the model attribute
	 * 
	 * Also validates the type of the input value
	 */
	public function __set($key, $value)
	{
		if (!$this->isModelAttribute($key) || empty($value))
			return;
		
		// Validate type
		$inputType = gettype($value);
		$requiredType = $this->getRequiredType($key);
		if ($inputType !== $requiredType)
		    throw new InvalidArgumentException("{$key} should be of type {$requiredType} (was {$inputType})");
		
		// Call setter if it exists, otherwise assign value to property
		$setter = sprintf('set%s', ucfirst($key));
		if (method_exists($this, $setter))
		    $this->$setter($value);
		else if (property_exists($this, $key))
		    $this->$key = $value;
	}
	
	/**
	 * Checks if model attribute is set
	 */
	public function __isset($key)
	{
		if ($this->isModelAttribute($key))
		{
			if (property_exists($this, $key))
				return isset($this->$key);
		}
	}
	
	/**
	 * Unsets the model attribute
	 */
	public function __unset($key)
	{
		if ($this->isModelAttribute($key))
		{
			if (property_exists($this, $key))
				unset($this->$key);
		}
	}
	
	/**
	 * Forwards accessor calls to the corresponding model attribute
	 * 
	 * A call to $obj->setUsername($value) is equal to $obj->username = $value
	 */
	public function __call($name, array $arguments)
	{
		if (strlen($name) < 4)
			return;
		
		$accessorMethod = substr($name, 0, 3);
		$attributeName = lcfirst(substr($name, 3));
		if ($this->isModelAttribute($attributeName))
		{
			if ($accessorMethod == 'get')
			{
				return $this->__get($attributeName);
			}
			else if ($accessorMethod == 'set')
			{
				if (count($arguments) == 1)
				{
					$value = $arguments[0];
					$this->__set($attributeName, $value);
				}
			}
		}
	}
	
	
	// ---
	// Helper methods
	// ---
	
	/**
	 * Checks if the key is a model attribute
	 */
	private function isModelAttribute($key)
	{
		return array_key_exists($key, $this->getModelAttributes());
	}
	
	/**
	 * Gets the required type for the model attribute
	 */
	private function getRequiredType($key)
	{
		$modelAttributes = $this->getModelAttributes();
		return $modelAttributes[$key];
	}
}