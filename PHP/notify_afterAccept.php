<?php
include_once 'database_config.php';

$reqid  = $_POST['requestid'];

$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");



$array1 = Array();
$array2 = Array();
$array3 = Array();
$array4 = Array();
$array5 = Array();
$array6 = Array();


$sql1="SELECT * FROM `request` where `requestid` = '$reqid'";

$result1 = mysqli_query($conn, $sql1);
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC))
{
	
		$array1[] = $row['pickuptime'];
		$array2[] = $row['typefood'];
		$array3[] = $row['donoraddress'];
		$array4[] = $row['donorphonenumber'];
		$array5[] = $row['recepientname'];
}

$sql1="SELECT * FROM `recipient` where `fullname` = '$array5[0]'";

$result1 = mysqli_query($conn, $sql1);
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC))
{
		$add = $row['address'];
		$city = $row['City'];
		$state = $row['State'];
		$country = $row['Country'];
		$zip = $row['zip'];
		
} 

$fulladdres = $add.",".$city.",".$state." ".$country."-".$zip;
$array2[0] = strtr($array2[0], array('"' => '','[' => '',']' => ''));
	

$list = array();

$list[] = array('donoraddreess' => $array3[0], 'pickuptime' => $array1[0],'typefood'=>$array2[0],'donorname'=>$array4[0],'recepientname'=>$array5[0],'recepientadd'=> $fulladdres);
	

$response_data = array("status"=>200, "data"=>$list);
$response= json_encode($response_data);
echo $response;
?>