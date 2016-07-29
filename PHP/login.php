<?php
error_reporting(E_ERROR | E_PARSE);
include_once 'database_config.php';

date_default_timezone_set('America/Los_Angeles');

$email = $_POST['email'];

$pass= $_POST['password'];

$domain=strstr($email,'@');

$domain1=substr($domain,1);

$has_dns_mx_record = checkdnsrr($domain1,"MX");

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    
    //echo '<span style="color:red; text-align:center;">ERROR! The EMAIL ID is not a valid one!</span>';
    echo '{"status":401,"msg":"ERROR! The EMAIL ID is not a valid one!"}';
    die();
}
if($has_dns_mx_record==false)
{
    //echo '<span style="color:red; text-align:center;">ERROR! This domain does not exist!!! Please enter a proper email id.</span>';
     echo '{"status":401,"msg":"ERROR! This domain does not exist!!! Please enter a proper email id"}';
    die();
}
//$sql = "SELECT * FROM recipient where `email` = '$email'";       
$sql = "SELECT * FROM donor WHERE `email` = '$email'";
$conn = get_connection();
$result = $conn->query($sql);
$row = $result->fetch_assoc();
//set role as donor if match occurs
if(!($row))
{
    $sql = "SELECT * FROM volunteer WHERE `email` = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
	//set role as volunteer if match occurs
    if(!($row))
    {
        $sql = "SELECT * FROM recipient WHERE `email` = '$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
		//set role as recipient if match occurs
        if(!($row))
        {
            echo '{"status":401,"msg":"ERROR!This EMAIL ID  does not exist in our records. Please check your EMAIL ID !!!"}';
            die();
        }
        else
        {
            $roll = "recipient";
        }
    }
    else
    {
        $roll ="volunteer";
    }
} 
else
{
    $roll = "donor";
}

$pass = md5($pass);   
if(strcmp($row["password"],$pass)==0)
{
	//if role is donor
    if(strcmp("donor",$roll)==0)
    {
	     echo '{"status":200,"url":"donoractivitypage.html","role":"donor"}';
    }
    else if(strcmp("volunteer",$roll)==0)
    {
	   echo '{"status":200,"url":"volactivitypage.html","role":"volunteer"}';
    }
    else
    {
         echo '{"status":200,"url":"recactivitypage.html","role":"recipient"}';   
    }
    exit();
}
else
{
    echo '{"status":401,"msg":"ERROR! INVALID EMAIL ID or PASSWORD!!!"}';
}
 
$conn->close();
?>