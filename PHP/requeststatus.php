<?php
include_once 'database_config.php';

$email = $_POST['email'];
$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");

$sql1="SELECT * FROM `request` where `donoremailid` = '$email'";

$result1 = mysqli_query($conn, $sql1);
while ($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) 
{
      if(strtotime($row['pickuptime']) > time())
	  {
	  	if($row[volunteeremailid] != "0")
	  	{
		   $vemail = $row['volunteeremailid'];
		   $vname = $row['volunteername']; 
		   $recipient =$row['recepientname'];
	        }
	  } 
}
$sql1="SELECT * FROM `rating` where `volunteerid` = '$vemail'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
if($row)
{
	$rating = round($row['rating'],2);
}
else
{
    $rating ="Not Available";

}

$sql1="SELECT * FROM `volunteer` where `email` = '$vemail'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
$num = $row['phonenumber'];

if($vemail === null)
{
	$flag = 0;
}
else
{
	$flag = 1;
}

$msg = "Your request for donation to ".$recipient." has been Accepted by ".$vname.".\nRating: ".$rating."\n, Contact number: ".$num;

$response_data = array("status"=>200, "data"=>$msg, "empty" => $flag);
$response= json_encode($response_data);
echo $response;
?>