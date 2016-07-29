<?php
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('America/Los_Angeles');
$i=0;
$rollofuser = $_POST['role'];

$fullname = $_POST['fullname'];

$address = $_POST['address'];

$city= $_POST['city'];

$state= $_POST['state'];

$country= $_POST['country'];

$zip= $_POST['zip'];

$website= $_POST['website'];

$email = $_POST['email'];

$password = $_POST['password'];

$startTime=$_POST['startTime'];

$endTime= $_POST['endTime'];

$phonenumber= $_POST['phonenumber'];

$typeFood= json_encode($_POST['typeFood']);

$conn=get_connection();
//$msg="";
$webmsg="";
$mailmsg="";
$databasemsg="";


if(strcmp($rollofuser,"recipient")==0){
//$url=substr($website,11); //website should be of format: http://www.abc.com
$url=split ("\www\.", $website);
$url=$url[1];

if ((filter_var($website, FILTER_VALIDATE_URL) == false))
{
	$i=1;
   // $msg=$msg."ERROR!invalid website ";
   $webmsg=$webmsg."ERROR!invalid website. ";
	$status=401;
    //echo '{"status":401,"msg":"ERROR!invalid website"}';
} 

	$valid=checkdnsrr($url,"A"); //check the domain name
	if(!($valid))
	{
		$i=1;
		//echo '{"status":401,"msg":"ERROR!Domain does not exist for website"}';
		$status=401;
		$webmsg=$webmsg."ERROR! Domain does not exist for website. ";
		
	}
}

$domain=strstr($email,'@');

$domain1=substr($domain,1);

$has_dns_mx_record = checkdnsrr($domain1,"MX");


if(!filter_var($email, FILTER_VALIDATE_EMAIL))

{

	$i=1;
	$status=401;
	$mailmsg=$mailmsg."ERROR!The EMAIL ID is not a valid one!";

	//echo '{"status":401,"msg":"ERROR!The EMAIL ID is not a valid one!"}';


}

if($has_dns_mx_record==false)

{

	$i=1;
	$status=401;
	$mailmsg=$mailmsg."ERROR! This domain does not exist!!! Please enter a proper email id.";

	//echo '{"status":401,"msg":"ERROR! This domain does not exist!!! Please enter a proper email id."}';

}


$sql2="SELECT * FROM `donor` where `email`='$email'";
$sql3="SELECT * FROM `volunteer` where `email`='$email'";
$sql4="SELECT * FROM `recipient` where `email`='$email'";

//$result1=$conn->query($sql2);
//echo $result1;
$result1 = mysqli_query($conn, $sql2);
$result2 = mysqli_query($conn, $sql3);
$result3 = mysqli_query($conn, $sql4);
//$row=$result1->fetch_assoc();

if (mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0 || mysqli_num_rows($result3) > 0 )

{

	$i=1;
	$status=401;
	$databasemsg=$databasemsg."ERROR! The User Already Exists .Please Give a unique email id !!!";

	//echo '{"status":401,"msg":" ERROR! The User Already Exists .Please Give a unique email id !!!"}';
	

}



if($i==0)

{

$password = md5($password);

if(strcmp($rollofuser,"donor")==0)
{
	$sql = "INSERT INTO `donor` VALUES ('$fullname', '0', '$email', '$password', '0', '0', '0', '0', '0','0')";
}
elseif (strcmp($rollofuser, "volunteer")==0)
{
 	$sql = "INSERT INTO volunteer VALUES ('$fullname', '$phonenumber', '$email', '$password', '$address', '$city', '$state', '$country', '$zip','0')";    
}
else
{
	 	$sql = "INSERT INTO recipient  VALUES ('$fullname', '$address', '$city', '$state', '$country', '$zip', '$website', '$email', '$password', '$startTime', '$endTime','$typeFood','0')";   
}

if ($conn->query($sql) === TRUE) 
{
	echo '{"status":200,"msg":"You have been registered successfully. You may now Log in!","url":"FightHunger.html"}';  
} 

else {
	$i=1;
   $status=401;
   $databasemsg=$databasemsg."ERROR!Database insertion failed.";
}

}

$conn->close();
if($i==1) {
$response_data = array("status"=>$status, "websitemsg"=>$webmsg, "emailmsg"=>$mailmsg, "databasemsg"=>$databasemsg);
header('Content-Type: application/json');
echo json_encode($response_data); 
}
?>