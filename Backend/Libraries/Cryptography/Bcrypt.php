<?php

/**
 * Bcrypt Wrapper
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
class Bcrypt
{
	private $cost = 7;
	private $salt;
	private static $saltFormat = '$2a$%02u$%s$'; // From php.net: Blowfish hashing with a salt as follows: "$2a$", a two digit cost parameter, "$", and 22 digits from the alphabet "./0-9A-Za-z". Using characters outside of this range in the salt will cause crypt() to return a zero-length string. The two digit cost parameter is the base-2 logarithm of the iteration count for the underlying Blowfish-based hashing algorithmeter and must be in range 04-31, values outside this range will cause crypt() to fail.
	
	// ---
	// Constructor
	// ---
	
	public function __construct($cost = 0, $salt = '')
	{
		$this->setCost($cost);
		$this->setSalt($salt);
	}
	
	
	// ---
	// Main methods
	// ---
	
	public static function available()
	{
		return (CRYPT_BLOWFISH == 1);
	}
	
	public function hash($password)
	{
		if (!$this->available())
			return null;
		
		$crypt_salt = sprintf(self::$saltFormat, $this->getCost(), $this->getSalt());
		
		$hash = crypt($password, $crypt_salt);
		return $hash;
	}
	
	
	// ---
	// Accessors/mutators
	// ---
	
	public function getCost()
	{
		return $this->cost;
	}
	
	public function setCost($cost)
	{
		if (is_numeric($cost) && $cost >= 4 && $cost <= 31)
			$this->cost = $cost;
	}
	
	public function getSalt()
	{
		return $this->salt;
	}
	
	public function setSalt($salt)
	{
		$this->salt = $salt;
	}
}