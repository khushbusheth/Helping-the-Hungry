<?php
include_once 'database_config.php';

//$email ="khushbu11@gmail.com";

$email = $_POST['email'];
$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");
$date = date("Y-m-d");
$thismonth = date("m",strtotime($date));
$today = date("d",strtotime($date));
$time = date("h:i:sa"); 

$rejids= Array();
//fetching rejectedids
$sql1="SELECT * FROM `rejectedrequest` where `volunteerid` = '$email'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
if($row)
{
  $rids = $row["rejectedid"];
  $rejids = explode(",",$rids);
}


// fetching pickup time for the not accepted requests
$array1 = Array();
$array2 = Array();
$array3 = Array();
$reqid = Array();
$vid = '0';
$j = 0;
$sql1="SELECT * FROM `request` where `volunteeremailid` = '$vid'";

$result1 = mysqli_query($conn, $sql1);
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
{
      if(strtotime($row['pickuptime']) > time())
	  {
	  	if(!(in_array($row['requestid'], $rejids)))
	  	{
		   $array1[$j] = $row['pickuptime'];
		   $array2[$j] = $row['typefood']; 
		   $array3[$j] = $row['donoraddress'];  
		   $reqid[$j]  = $row['requestid'];
		   $j++; 
		} 
	  }
}

$d=0;
while($d<$j)
{
	$array2[$d] = strtr($array2[$d], array('"' => '','[' => '',']' => ''));
	$d++;
}

$list = array();
$k = 0;
while($k < $j)
{
    $list[] = array('donoraddreess' => $array3[$k], 'pickuptime' => $array1[$k],'typefood'=>$array2[$k] ,'requestnum'=> $reqid[$k]);
    $k++;
}

$response_data = array("status"=>200, "data"=>$list);
$response= json_encode($response_data);
echo $response;
?>