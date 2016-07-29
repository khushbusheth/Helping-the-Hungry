<?php
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('America/Los_Angeles');

$email = $_POST['email'];
//$email=$_SESSION['email'];

$pass = $_POST['password'];
$msg="";

$i= 0;
if(!(preg_match("/[A-Z]+/",$pass)))

{

    $i=1;

    //echo '{"status":401,"msg":"ERROR! The password must contain atleast one capital alphabet."}';
	$status=401;
	$msg=$msg."ERROR! The password must contain atleast one capital alphabet.";

}

if(!(preg_match("/[0-9]+/",$pass)))

{

    $i=1;
    //echo '{"status":401,"msg":"ERROR! The Password must contain atleast one number"}';
    //die();
	$status=401;
	$msg=$msg."ERROR! The password must contain atleast one capital number.";

}

if(!(preg_match("/[a-z]+/",$pass)))

{

    $i=1;
    //echo '{"status":401,"msg":"ERROR! The Password must contain atleast one small alphabet"}';
    //die();
	$status=401;
	$msg=$msg."ERROR! The password must contain atleast one small alphabet.";
	

}

if(!(preg_match('/[#@!$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\"]+/',$pass)))

{

    //echo "ERROR! The Password must contain atleast one special character\n";
  //  echo '{"status":401,"msg":"ERROR! The Password must contain atleast one special character"}';
    $i=1;
	$status=401;
	$msg=$msg."ERROR! The password must contain atleast one small special character.";

    //die();

}

if($i==0)
{
        $conn = get_connection();
        $password = md5($pass);
        $sql = "SELECT * FROM donor WHERE `email` = '$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($row)
        {
            $sql = "UPDATE donor SET password = '$password' WHERE email = '$email'"; //change query: based on role or in all 3 tables
        }
        else
        {
            $sql = "SELECT * FROM volunteer WHERE `email` = '$email'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc(); 

            if($row)
            {
                $sql = "UPDATE volunteer SET password = '$password' WHERE email = '$email'";
            }

            else
            {
                $sql = "UPDATE recipient SET password = '$password' WHERE email = '$email'";   
            }  
        }
        // set timeout for email link validity???
        
        if ($conn->query($sql) === TRUE) 
        {

            //echo '{"status":200,"msg":"Password updated successfully.","url":"FightHunger.html"}';
            echo '{"status":200,"msg":"Password updated successfully.Please login to our app with your new password"}';
            
           
 	    //header("Location:../HTML/FightHunger.html");
 	    //exit;

        } 
        else 
        {
			$i=1;
			$status=401;
			$msg=$msg."ERROR updating record";
           // echo "Error updating record: " . $conn->error;
        }
        $conn->close();
		
}
if($i == 1)
		{
			$response_data = array("status"=>$status, "msg"=>$msg);
			header('Content-Type: application/json');
			echo json_encode($response_data);
		}
?>