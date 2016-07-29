<?php
include_once 'database_config.php';
date_default_timezone_set('America/Los_Angeles');
$email = $_POST['email'];
$m= $_POST['month'];
$conn=get_connection();

$sql1="SELECT pickuptime FROM `request` where `volunteeremailid` = '$email'";

$result1 = mysqli_query($conn, $sql1);
$array2 = Array();
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
{
    $array2[] =  $row['pickuptime'];  
}

$count = count($array2);
$i=0;
$j=0;
$array3 = Array();
while($i<$count)
{
	 if(strtotime($array2[$i]) < time())
	 {
	$month = date("m",strtotime($array2[$i]));
	if($month == $m)
	{
		$array3[$j] = $array2[$i];
		$j++;
	}
	}
	$i++;
}


$count = count($array3);
$i=0;
$array6 = Array();
while ($i < $count)
{ 
	$sql1="SELECT donorname FROM `request` where `volunteeremailid` = '$email' AND `pickuptime` = '$array3[$i]'";
	$result1 = mysqli_query($conn, $sql1);

	$array1 = Array();
	while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
	{
    	$array1[] =  $row['donorname'];  
	}
	$array6[$i] = $array1[0];
	$i++;
}


$i=0;
$array9 = Array();
while ($i < $count)
{ 
	$sql1="SELECT recepientname FROM `request` where `volunteeremailid` = '$email' AND `pickuptime` = '$array3[$i]'";
	$result1 = mysqli_query($conn, $sql1);

	$array10 = Array();
	while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
	{
    	$array10[] =  $row['recepientname'];  
	}
	$array9[$i] = $array10[0];
	$i++;
}
//echo $array9[1];

$i=0;
$array7 = Array();
while ($i < $count)
{ 
	$sql1="SELECT typefood FROM `request` where `volunteeremailid` = '$email' AND `pickuptime` = '$array3[$i]'";
	$result1 = mysqli_query($conn, $sql1);

	$array8 = Array();
	while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
	{
    	$array8[] =  $row['typefood']; 
	}
	$array7[$i] = $array8[0];
	$i++;
}
	$d= 0;
	$c = count($array7);
	while($d<$c)
	{
		$array7[$d] = strtr($array7[$d], array('"' => '','[' => '',']' => ''));
		$d++;
	}

$list = array();
$k = 0;
while($k < $count)
{
    $list[] = array('msg'=>"You have volunteered to transfer food",'donorname' => $array6[$k], 'time' => $array3[$k],'recname'=>$array9[$k],'typefood'=>$array7[$k]);
    $k++;
}

$response_data = array("status"=>200, "data"=>$list);
//echo $count;
//header('Content-Type: application/json');
$response= json_encode($response_data);
echo $response;
/*$response= json_decode($response,true);
//$volname=$response['data'][0]['volname'];

$k = 0;
while($k < $count)

{
	header('Content-Type: application/json');
	$recipientName=$response['data'][$k]['recname'];
	$volname=$response['data'][$k]['volname'];
	$time1=$response['data'][$k]['time'];
	$foodType=$response['data'][$k]['typefood'];
	$k++;
	//echo $recipientName;
	//echo $volname;
	//echo $time1;
	//echo $foodType;
}*/
?>