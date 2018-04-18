<?php

	class DB{

		private $conn;

		function __construct($config){

			$this->conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

			if($conn->connect_error)
			{

				die("Connection failed " . $conn->connect_error);

			} else {

				echo "Connection to database $dbname is established<br>";

			}

		}

		public function query($sql){

			if($this->conn->query($sql) !== true)
			{

				return $this->conn->error . "<br>";

			}

		}

		public function querySelect($sql){

			return $this->conn->query($sql);

		}

	}