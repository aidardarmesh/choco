<?php
	
	require_once("DB.php");
	require_once("Action.php");
	require_once("config.php");

	define("FILENAME", "actions.csv");

	// Creating DB object and establishing connection
	$DB = new DB($config);

	// Creating table of actions
	Action::createTable($DB);

	// Exporting data from CSV file omitting header section
	// Writing them to DB
	$actions = Action::exportFrom(FILENAME);

	foreach($actions as $action){

		$action->validate()->saveTo($DB);

	}

	// Choosing random action
	$rand_action = Action::getRandom($DB);

	$rand_action->changeStatus($DB);

	// Showing in CSV format random action
	echo $rand_action->getCsv();

	// Getting data again from CSV file and showing their URLs
	foreach($actions as $action){

		echo $action->getUrl() . "<br>";

	}