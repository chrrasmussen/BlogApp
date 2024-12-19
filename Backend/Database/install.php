<?php
	// include configurations
	include("./../EnvironmentVariables.php");

	$host = $_ENV['mysql']['host'];
	$port = $_ENV['mysql']['port'];
	$database = $_ENV['mysql']['database'];
	$username = $_ENV['mysql']['username'];
	$password = $_ENV['mysql']['password'];

	$sql_file = './Tables.sql';

	// connect to mysql
	$db = new mysqli($host, $username, $password, $database, $port);

	// check if database file exists
	if (!is_file($sql_file))
	{
		// show error
		print("Could not find '{$sql_file}'. Are your files placed properly? Please check");

		// terminate file
		exit;
	}

	// open file
	if (!($fp = fopen($sql_file, "r")))
	{
		// show error
		print("Read of {$sql_file} failed. Please check permissions.");

		// terminate file
		exit;
	}

	// get contents from file
	$contents = fread($fp, filesize($sql_file));

	// close file
	fclose($fp);

	// parse out drop, create, and insert statments
	preg_match_all("/(DROP TABLE.*?);/s", $contents, $drop_statements);
	preg_match_all("/(CRE.*?);/s", $contents, $create_statements);
	preg_match_all("/(INSER.*?);[\n\r]/s", $contents, $insert_statements);

	// loop through drop statements
	foreach ($drop_statements[1] as $query)
	{
		// drop queries
		mysqli_query($db, $query) or
			die($db->error. " drop table failed: {$query}.");
	}

	// loop through create statements
	foreach ($create_statements[1] as $query)
	{
		// create queries
		mysqli_query($db, $query) or
			die($db->error. " create table failed: {$query}.");
	}

	// loop through insert statements
	foreach ($insert_statements[1] as $query)
	{
		// insert queries
		mysqli_query($db, $query) or
			die($db->error . " insert failed: {$query}.");
	}

	// show text
	print("Database \"{$database}\" successfully created.");
?>