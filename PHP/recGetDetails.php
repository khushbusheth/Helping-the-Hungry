<?php
include_once 'database_config.php';
date_default_timezone_set('America/Los_Angeles');
$email = $_POST['email']; //collect recipient's email id
$id1=$_POST['id1'];
$todo=$_POST['todo'];
$conn=get_connection();
if($todo=="rate")
{
	$sql20="SELECT * FROM `request` WHERE requestid = '$id1'";
	$result_20 = mysqli_query($conn, $sql20);
		$row_20 = $result_20->fetch_assoc();
		$vmail=$row_20['volunteeremailid'];
		$vname=$row_20['volunteername'];
		
		
	$response_data20 = array("status"=>200,"vname"=>$vname,"vmail"=>$vmail);
	echo json_encode($response_data20);
}
$conn->close();
?>