<?php

/**
 * Environment variables
 *
 * @author Christian Rasmussen <christian.rasmussen@me.com>
 */

// Prod
$_ENV['mysql']['host'] = 'blogapp_db_1';
$_ENV['mysql']['port'] = 3306;
$_ENV['mysql']['database'] = 'blogapp';
$_ENV['mysql']['username'] = 'root';
$_ENV['mysql']['password'] = 'root';

$_ENV['app']['baseURL'] = 'http://christian.rasmussen.io/cv/blogapp/preview';

// Dev
// $_ENV['mysql']['host'] = 'db';
// $_ENV['mysql']['port'] = 3307;
// $_ENV['mysql']['database'] = 'blogapp';
// $_ENV['mysql']['username'] = 'root';
// $_ENV['mysql']['password'] = 'root';

// $_ENV['app']['baseURL'] = 'http://' . $_SERVER['SERVER_NAME'] . ':8888/BlogApp/public_html';