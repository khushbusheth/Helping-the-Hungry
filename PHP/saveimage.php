<?php
header('Access-Control-Allow-Origin: *');
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE); 
$i=1;
$email = $_POST['email'];
$mainemail=$email;
$email = strtr($email, array('.' => '','@' => ''));
$target_dir = "../uploads";
$result=mkdir($target_dir,0755);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType=strtolower($imageFileType);
$msg="";
$src="";

$filename=$target_dir . '/'.$email.".".$imageFileType;//Appends the filename to directory //path

$src = "../uploads".'/'.$email.".".$imageFileType;
$conn=get_connection();

if ($_FILES["fileToUpload"]["size"] > 10000000) { //file size greater than the specified bytes
    //$msg = $msg."Sorry, your file is too large.";
    $i= 0;
}
if($i==0)
{
    //$msg = $msg."**Error in uploading the file";
    echo '{"status":401,"msg":"Sorry, file already exists."}';

}
else{
    $flag = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filename);
    if ($flag == true) {
        //$src = $src.$filename;
        $src="http://cmpe235sjsu.com/295B_version6"."/uploads".'/'.$email.".".$imageFileType;
        $msg = $msg."file uploaded";
        $response_data = array("status"=>200, "msg"=>$msg, "src"=>$src);
		$sql="UPDATE donor SET image = '$src' WHERE email = '$mainemail'";
		//echo $sql; 
		$result=$conn->query($sql);
		//echo $result;
		
		header('Content-Type: application/json');
        echo json_encode($response_data); 
        //echo $src;

    } else {
        $msg = $msg."Sorry, there was an error uploading your file.";
		$sql="SELECT image,fullname from donor where email='$mainemail'";
		$result=$conn->query($sql);
		$row=$result->fetch_assoc();
		$src=$row["image"];
		$name=$row["fullname"];
        $response_data = array("status"=>401, "msg"=>$msg, "src"=>$src,"name"=>$name, "filename"=>$filename , "returntype"=>$flag);
        header('Content-Type: application/json');
        echo json_encode($response_data); 
        //echo '{"status":401,"msg": "Sorry, there was an error uploading your file."}';
    }
	$conn->close();
}
?>