<?php

	class Action{

		private static $table_name = "actions";

		public static function createTable($db){

			$sql = "CREATE TABLE " . self::$table_name . " (
										id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
										name VARCHAR(100) NOT NULL,
										start_date INTEGER(9) NOT NULL,
										finish_date VARCHAR(50) NOT NULL,
										status VARCHAR(3) NOT NULL
			)";

			echo $db->query($sql);

		}

		public static function getTableName(){

			return self::$table_name;

		}

		public static function getFrom($db, $id){

			$result = $db->querySelect("SELECT * FROM actions WHERE id=" . $id);
			$row = mysqli_fetch_row($result);
			return new self($row);

		}

		private $id;
		private $name;
		private $start_date;
		private $finish_date;
		private $status;

		function __construct($arr){

			$this->id = $arr[0];
			$this->name = $arr[1];
			if(strpos($arr[2], "-") !== false) $this->start_date = strtotime($arr[2]);
			else $this->start_date = $arr[2];
			$this->finish_date = $arr[3];
			$this->status = $arr[4];

		}

		function saveTo($db){

			$sql = "INSERT INTO " . self::$table_name . " (id, name, start_date, finish_date, status) " . 
				   "VALUES (" . $this->id . ",'" . $this->name . "'," . $this->start_date . ",'" . $this->finish_date . "','" . $this->status . "')";

			echo $db->query($sql);

		}

		function changeStatus($db){

			$opposite = ($this->status == "On") ? "Off" : "On";

			$sql = "UPDATE " . self::$table_name . " SET status='" . $opposite . "' WHERE id=" . $this->id;
			
			echo $db->query($sql);

		}

		function getCsv(){

			return $this->id . ";" . 
		 		   $this->name . ";" . 
		 		   date("d-m-Y", $this->start_date) . ";" . 
		 		   $this->finish_date . ";" . 
		 		   $this->status . "<br>";

		}

		function getUrl(){

			$name = mb_strtolower($this->name);
			$name = strtr($name, array( "а" => "a",
										"ый" => "iy",
										"ые" => "ie",
										"б" => "b",
										"в" => "v",
										"г" => "g",
										"д" => "d",
										"е" => "e",
										"ё" => "yo",
										"ж" => "zh",
										"з" => "z",
										"и" => "i",
										"й" => "y",
										"к" => "k",
										"л" => "l",
										"м" => "m",
										"н" => "n",
										"о" => "o",
										"п" => "p",
										"р" => "r",
										"с" => "s",
										"т" => "t",
										"у" => "u",
										"ф" => "f",
										"х" => "kh",
										"ц" => "ts",
										"ч" => "ch",
										"ш" => "sh",
										"щ" => "shch",
										"ь" => "",
										"ы" => "y",
										"ъ" => "",
										"э" => "e",
										"ю" => "yu",
										"я" => "ya",
										"йо" => "yo",
										"ї" => "yi",
										"і" => "i",
										"є" => "ye",
										"ґ" => "g"));
			$name = preg_replace("/\W+/", "-", $name);
			$name = trim($name, "-");
			return $this->id . "/" . $name;

		}

	}