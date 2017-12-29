<?php
    class DB_Connection {
        private $mPdo;
        function __construct() {
            $this->mPdo = new PDO('mysql:host=localhost;dbname=robosax_2017_12_17;charset=utf8','root','');
        }
        function getPDO() {
            return $this->mPdo;
        }
    }
    $db_connection = new DB_Connection();
	$pdo = $db_connection -> getPDO();
?>
