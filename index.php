<?php
	
	require_once("DB.php");
	require_once("Action.php");

	define("FILENAME", "actions.csv");

	// Creating DB object and establishing connection
	$DB = new DB("localhost", "root", "", "choco");

	// Creating table of actions
	Action::createTable($DB);

	// Exporting data from CSV file omitting header section
	// Writing them to DB
	$row = 1;
	if(($handle = fopen(FILENAME, "r")) !== FALSE)
	{

		while(($line = fgetcsv($handle, 10000, ";")) !== FALSE)
		{

			if($row === 1)
			{

				$row++;
				continue;

			} else $row++;

			$action = new Action($line);
			$action->saveTo($DB);

		}

		fclose($handle);

	}

	// Choosing random id of actions
	$result = $DB->querySelect("SELECT id FROM " . Action::getTableName());

	$ids = [];

	while($row = $result->fetch_assoc())
	{

		$ids[] = $row["id"];

	}

	$rand_id = $ids[rand(0, $result->num_rows-1)];

	// Getting back random action and changing its status
	$rand_action = Action::getFrom($DB, $rand_id);

	$rand_action->changeStatus($DB);

	// Showing in CSV format random action
	echo $rand_action->getCsv();

	// Getting data again from CSV file and showing their URLs
	$row = 1;
	if(($handle = fopen(FILENAME, "r")) !== FALSE)
	{

		while(($line = fgetcsv($handle, 10000, ";")) !== FALSE)
		{

			if($row === 1)
			{

				$row++;
				continue;

			} else $row++;

			$action = new Action($line);
			echo $action->getUrl() . "<br>";

		}

		fclose($handle);

	}