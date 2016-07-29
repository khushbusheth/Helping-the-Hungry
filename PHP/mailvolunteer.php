<?php
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE);
//date_default_timezone_set('America/Los_Angeles');

$email = $_POST['email'];


$conn=get_connection();

$sql1="SELECT donorname,donoraddress,recepientname,pickuptime FROM `request` where `donoremailid`='$email' ORDER BY requestid DESC LIMIT 1";

$result1 = mysqli_query($conn, $sql1);

$row=$result1->fetch_assoc();

$donorfullname =$row["donorname"];
$address=$row["donoraddress"];
$recepientname=$row["recepientname"];
$time=$row["pickuptime"];


	$msg = "New Donation Request by ".$donorfullname."\nAt location: " .$address . "\nto Organization: " . $recepientname."\nPreferred Pickup Time:".$time; 
	$sql2 = "SELECT * FROM `volunteer`";
	$result2 = mysqli_query($conn, $sql2);
	$array1 = Array();
    while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) 
    {
        $array1[] =  $row['email'];  
    }
    
    $c = count($array1);
    $i = 0;
    $value=1;
    while ($i < $c) 
    {
    	$email = $array1[$i];
    	$value=$value && mail ($email,"Food for all: New Donation Request",$msg);
    	$i++;
    }
	$conn->close();
   $response_data = array("status"=>200,"msg"=>"Your request has been broadcasted to all volunteers.","url"=>"donoractivitypage.html","value"=>$value);
    header('Content-Type: application/json');
    echo json_encode($response_data);
 
 

?>