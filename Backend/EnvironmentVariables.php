<?php

/**
 * Environment variables
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */
if ($_SERVER['SERVER_NAME'] == 'db-kurs.hit.no')
{
	$_ENV['mysql']['host'] = 'localhost';
	$_ENV['mysql']['port'] = 3306;
	$_ENV['mysql']['database'] = 'db080599';
	$_ENV['mysql']['username'] = 's080599';
	$_ENV['mysql']['password'] = 'rootroot';
	
	$_ENV['app']['baseURL'] = 'http://db-kurs.hit.no/~080599';
}
else //($_SERVER['SERVER_NAME'] == 'localhost')
{
	$_ENV['mysql']['host'] = 'localhost';
	$_ENV['mysql']['port'] = 8889;
	$_ENV['mysql']['database'] = 'blogapp';
	$_ENV['mysql']['username'] = 'root';
	$_ENV['mysql']['password'] = 'root';
	
	$_ENV['app']['baseURL'] = 'http://' . $_SERVER['SERVER_NAME'] . ':8888/BlogApp/public_html';
}