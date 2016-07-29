<?php
header('Access-Control-Allow-Origin: *');
error_reporting(E_ERROR | E_PARSE);
$servername = "localhost";

$username = "cmpe235s_manasa"; //"cmpe235s_manasa";

$password1= "Sanjose123!"; //"Sanjose123!";

$dbname = "cmpe235s_CMPE295"; 

/*
	returns connection object of the database.
*/
function get_connection(){
global $servername, $username, $password1, $dbname;
	// Create connection
	$conn = mysqli_connect($servername, $username, $password1, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	return $conn;
}

?>