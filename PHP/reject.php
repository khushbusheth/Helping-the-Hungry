<?php
include_once 'database_config.php';
$email = $_POST['email'];
//$rid = 11;
$rid =$_POST['rejID'];
$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");


// check wheather entry is der if der append rejected id else insert 
$sql1="SELECT * FROM `rejectedrequest` where `volunteerid` = '$email'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);

if($row)
{
		$newid = $row['rejectedid'].','.$rid;
		$sql = "UPDATE rejectedrequest SET rejectedid = '$newid' WHERE volunteerid = '$email'";
}
else
{
	$sql = "INSERT INTO `rejectedrequest` VALUES ('$email','$rid')";
}

if ($conn->query($sql) === TRUE) 
{
	$conn->close();
    $response_data = array("status"=>200,"msg"=>"Entry Added");
    echo json_encode($response_data);
}
else 
{
	$conn->close();
	$response_data = array("status"=>401,"msg"=>"Server down");
    echo json_encode($response_data);
} 


?>