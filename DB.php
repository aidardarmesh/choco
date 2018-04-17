<?php

	class DB{

		private $conn;

		function __construct($servername, $username, $password, $dbname){

			$this->conn = new mysqli($servername, $username, $password, $dbname);

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