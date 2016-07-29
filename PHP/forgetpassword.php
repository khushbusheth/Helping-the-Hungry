<?php
error_reporting(E_ERROR | E_PARSE);
include_once 'database_config.php';
$email= $_POST['email'];
//echo $_SESSION['email'];
$body = "Please click the link below to change password.(Link expires in 5 minutes)\nhttp://cmpe235sjsu.com/295B_version6/HTML/changepassword.html\n";
$sql = "SELECT * FROM donor WHERE `email` = '$email'";
$conn = get_connection();
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if(!($row))
{
    $sql = "SELECT * FROM volunteer WHERE `email` = '$email'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if(!($row))
    {
        $sql = "SELECT * FROM recipient WHERE `email` = '$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if(!($row))
        {
            echo '{"status":401,"msg":"ERROR!This EMAIL ID  does not exist in our records. Please check your EMAIL ID !!!"}';
            die();
        }
        else
        {
            
           mail ($email,"Food for all: Change Password",  $body);
           echo '{"status":200,"msg":"We have emailed you the link to change your password."}';
       }
}
    else
   {
          
mail ($email,"Food for all: Change Password",  $body);
echo '{"status":200,"msg":"We have emailed you the link to change your password."}';

   } 
}
else
{

mail ($email,"Food for all: Change Password",  $body);
echo '{"status":200,"msg":"We have emailed you the link to change your password."}';
//echo "Please check your mail for the further instruction.";
}
?>