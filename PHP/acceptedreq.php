<?php
include_once 'database_config.php';

$email = $_POST['emailid'];

$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");



$array1 = Array();
$array2 = Array();
$array3 = Array();
$array4 = Array();
$array5 = Array();
$array6 = Array();
$fulladdres = Array();
$flag = 0;
$i = 0;

$sql1="SELECT * FROM `request` where `volunteeremailid` = '$email'";

$result1 = mysqli_query($conn, $sql1);
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC))
{

	if(strtotime($row['pickuptime']) > time()||(time()-strtotime($row['pickuptime']))<= 10800)
	{	
		$flag = 1;
		$array1[] = $row['pickuptime'];
		$array2[] = $row['typefood'];
		$array3[] = $row['donoraddress'];
		$array4[] = $row['donorphonenumber'];
		$array5[] = $row['recepientname'];
		$i++;
	}
}

$j = 0;
while($j < $i)
{
$sql1="SELECT * FROM `recipient` where `fullname` = '$array5[$j]'";

$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);

		$add = $row['address'];
		$city = $row['City'];
		$state = $row['State'];
		$country = $row['Country'];
		$zip = $row['zip'];
		$fulladdres[$j] = $add.",".$city.",".$state." ".$country."-".$zip;
		
$j++;
}

$j = 0;
while($j < $i)
{
$array2[$j] = strtr($array2[$j], array('"' => '','[' => '',']' => ''));
$j++;
}
	

$list = array();

$k = 0;
while($k < $i)
{
$list[] = array('donoraddreess' => $array3[$k], 'pickuptime'=>$array1[$k],'typefood'=>$array2[$k],'donorname'=>$array4[$k],'recepientname'=>$array5[$k],'recepientadd'=> $fulladdres[$k]);
$k++;
}
	

$response_data = array("status"=>200, "data"=>$list, "empty"=> $flag);
$response= json_encode($response_data);
echo $response;
?>