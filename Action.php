<?php

	class Action{

		private static $table_name = "actions";

		public static function createTable($db){

			$sql = "CREATE TABLE IF NOT EXISTS " . self::$table_name . " (
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

		public static function getRandom($db){

			$result = $db->querySelect("SELECT COUNT(*) FROM " . self::getTableName());

			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			$rand_row = rand(0, ($row[0]-1));

			$result = $db->querySelect("SELECT * FROM " . self::getTableName() . " LIMIT " . $rand_row . ", 1");

			return new self(mysqli_fetch_array($result, MYSQLI_NUM));

		}

		public static function exportFrom($filename){

			$actions = [];

			if(file_exists(FILENAME)){

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

						$actions[] = new self($line);

					}

					fclose($handle);

					return $actions;

				}
				
			} else {

				echo "File " . FILENAME . " does not exist<br>";

			}

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

		static function sanitize($data){

			return htmlspecialchars(stripslashes(trim($data)));

		}

		function validate(){

			$this->id = self::sanitize($this->id);
			$this->name = self::sanitize($this->name);
			$this->start_date = self::sanitize($this->start_date);
			$this->finish_date = self::sanitize($this->finish_date);
			$this->status = self::sanitize($this->status);
			return $this;

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