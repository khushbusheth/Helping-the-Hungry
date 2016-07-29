<?php
include_once 'database_config.php';
date_default_timezone_set('America/Los_Angeles');
$email = $_POST['email']; //collect recipient's email id
//$email="abcn@gmail.com";
$id=$_POST['id'];
$dec=$_POST['decision'];
$conn=get_connection();
if(!strlen($id))
{
$sql="SELECT fullname from `recipient` where `email`='$email'";
$result = mysqli_query($conn, $sql);
$array1 = Array();
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$name=$row['fullname'];
$sql1="SELECT requestid,donorname,donoremailid,pickuptime,volunteeremailid,volunteername,donoraddress,typefood,donorphonenumber FROM `request` where `recepientname` = '$name'AND`recaccept`='no'"; // volunteeremail id should not be null
$result1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
while($row)
{
//print_r($row);
$pick=$row['pickuptime'];
$vol=$row['volunteeremailid'];
$to_time=strtotime($pick);
$from_time=time();

if(($from_time>$to_time)&&($vol!='0'))
{
unset($row['pickuptime']);
array_push($array1,$row); 
 }

$row = mysqli_fetch_array($result1, MYSQLI_ASSOC);
}
echo json_encode($array1);
}
else
{
	$sql3 = "UPDATE request SET recaccept = '$dec' WHERE requestid = '$id'";
	$conn->query($sql3);
	
	if($dec=="accept")
	{
		$sql_1="SELECT donoremailid,recepientname from `request` where `requestid`='$id'";
		$result_1 = mysqli_query($conn, $sql_1);
		
		$row_1 = mysqli_fetch_array($result_1, MYSQLI_ASSOC);
		
		$donormail=$row_1['donoremailid'];
		$recname=$row_1['recepientname'];
		
		
		$sql_2="SELECT fullname from `donor` where `email`='$donormail'";
		$result_2 = mysqli_query($conn, $sql_2);
		
		$row_2 = mysqli_fetch_array($result_2, MYSQLI_ASSOC);
		$donorname=$row_2['fullname'];
		
		$msg="HI ".$donorname.",\n".$recname." has received your food donation . Thank You!!!\nPlease Spread the good word and continue donating.\n\nRegards,\nFood For All Team";
		
		mail ($donormail,"FOOD FOR ALL: DONATION ACKNOWLEDGEMENT",$msg);
		$response_data = array("status"=>200,"msg"=>$msg);
		echo json_encode($response_data);
	}
	if($dec=="reject")
	{
		$sql_1="SELECT donoremailid,recepientname from `request` where `requestid`='$id'";
		$result_1 = mysqli_query($conn, $sql_1);
		
		$row_1 = mysqli_fetch_array($result_1, MYSQLI_ASSOC);
		$donormail=$row_1['donoremailid'];
		$recname=$row_1['recepientname'];
		//echo $donormail;
		$sql_2="SELECT website,email from `recipient` where `fullname`='$recname'";
		$result_2 = mysqli_query($conn, $sql_2);
		
		$row_2 = mysqli_fetch_array($result_2, MYSQLI_ASSOC);
		$website=$row_2['website'];
		$mailrec=$row_2['email'];
		
		$sql_3="SELECT fullname from `donor` where `email`='$donormail'";
		$result_3 = mysqli_query($conn, $sql_3);
		
		$row_3 = mysqli_fetch_array($result_3, MYSQLI_ASSOC);
		$donorname=$row_3['fullname'];
		
		
		$msg="HI ".$donorname.",\n".$recname." has denied your food donation . Contact the organization:\n\nWebsite:  ".$website."\n"."Email: ".$mailrec."\n\nRegards,\nFood For All Team";

		mail ($donormail,"FOOD FOR ALL: DONATION DENIAL!!",$msg);
		$response_data = array("status"=>200,"msg"=>$msg); //need to handle error scenario too
		echo json_encode($response_data);
	}
}
$conn->close();
?>