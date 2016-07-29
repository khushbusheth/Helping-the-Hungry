<?php
include_once 'database_config.php';
$email = $_POST['email'];
$rid =$_POST['accID'];

$conn=get_connection();
date_default_timezone_set("America/Los_Angeles");

$sql1="SELECT * FROM `volunteer` where `email` = '$email'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
$vname = $row['fullname'];
$num = $row['phonenumber'];

$vid='0';


$sql2 = "UPDATE request SET volunteeremailid = '$email' WHERE requestid = '$rid' AND volunteeremailid = '0'";
mysqli_query($conn,$sql2);
 if(!(mysqli_affected_rows($conn)))
{
	$msg="Hi. This request has already been accepted. Please ignore this and check your mail for further details.";
	$response_data = array("status"=>200,"msg"=>$msg);
	echo json_encode($response_data); 
	die();
	
}

$sql3 = "UPDATE request SET volunteername = '$vname' WHERE requestid = '$rid'";
$conn->query($sql3);
//mail to all remaining volunteers
$sql_req="SELECT donorname,donoraddress,pickuptime,typefood FROM `request` where requestid = '$rid'";
$result_req = mysqli_query($conn, $sql_req);
$row_req = mysqli_fetch_array($result_req, MYSQLI_ASSOC);
$sql_new="SELECT email,fullname FROM `volunteer` where `email` != '$email'";
$result_new=mysqli_query($conn, $sql_new);
$row_new = mysqli_fetch_array($result_new, MYSQLI_ASSOC);
$dname=$row_req['donorname'];
$daddr=$row_req['donoraddress'];
$ptime=$row_req['pickuptime'];
$tfood=$row_req['typefood'];
while($row_new)
{
	$volmail=$row_new['email'];
	$name=$row_new['fullname'];
	
	$msg="HI ".$name.",\n The below request has been already been accepted. Kindly don't respond.\n\ndonorname: ".$dname."\nAddress: ".$daddr."\nPickuptime: ".$ptime."\nTypeofFood: ".$tfood."\n\nRegards,\nFood For All Team";  
	mail ($volmail,"Food for all:Ignore the donation request",$msg);
	$row_new = mysqli_fetch_array($result_new, MYSQLI_ASSOC);
}
//end mail  to reminaing volunteers
$sql1="SELECT * FROM `rating` where `volunteerid` = '$email'";
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


$sql1="SELECT * FROM `request` where `requestid` = '$rid'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
$demailid = $row['donoremailid'];
$recipient =$row['recepientname'];


$sql1="SELECT * FROM `donor` where `email` = '$demailid'";
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
$dname = $row['fullname'];


$msg = "Hello ".$dname.",\nYour request for donation to " .$recipient. " has been accepted. Please find details below\n\nVolunteer name: ".$vname."\nPhone number: ".$num."\nRating: ".$rating; 

//echo $msg;

mail ($demailid,"Food for all:Your Donation Request has been Accepted",$msg);

$conn->close();

$response_data = array("status"=>200,"msg"=>"You have successfully confirmed the request!");
echo json_encode($response_data); 
?>