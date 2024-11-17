<?php
//include configuration files here
require_once "config.php";


//create db connections
$DBConnections = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);


//check connection errors
if ($DBConnections->connect_error) {
  die("Connection error: " . $DBConnections->connect_errno);
}
