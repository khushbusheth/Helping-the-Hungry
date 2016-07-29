<?php
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE);
//date_default_timezone_set('America/Los_Angeles');

$email = $_POST['email'];

$address = $_POST['address'];
$pickuptime = $_POST['pickuptime'];

$typeFood= json_encode($_POST['typeFood']);

$recepientname = $_POST['select-recipient'];
$phonenumber = $_POST["phone"];

$conn=get_connection();

$sql1="SELECT * FROM `donor` where `email`='$email'";

$result1 = mysqli_query($conn, $sql1);

$row=$result1->fetch_assoc();

$donorfullname =$row["fullname"];
//echo $donorfullname;



$sql = "INSERT INTO `request` VALUES ('0','$donorfullname','$email','0','0','$pickuptime','$address','$typeFood','$phonenumber','$recepientname','no')";


if ($conn->query($sql) === TRUE) 
{
	/*$msg = "New Donation Request by ".$donorfullname." At location: " .$address . " to Organization " . $recepientname."\n"; 
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
    }*/
	$conn->close();
  // $response_data = array("status"=>200,"msg"=>"Kudos!! Your food donation request has been submitted.","url"=>"donoractivitypage.html","value"=>$value);
  $response_data = array("status"=>200,"msg"=>"Kudos!! Your food donation request has been submitted.","url"=>"donoractivitypage.html");
    header('Content-Type: application/json');
    echo json_encode($response_data);
} 
else
{
	$conn->close();
    $response_data = array("status"=>401,"msg"=>"Donation Request could not be made. Try Again!!");
    header('Content-Type: application/json');
    echo json_encode($response_data);
}
?>
