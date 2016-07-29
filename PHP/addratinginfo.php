<?php
include_once 'database_config.php';
$email = $_POST['email'];

$dropdown = $_POST['volName'];
$pieces = explode(", ", $dropdown);
$rating = $_POST['star2']; //name attr of div or of the input elemen

$conn=get_connection();
$sql1="SELECT * FROM `request` where (`volunteername`='$pieces[0]' AND `pickuptime`='$pieces[1]')";
$result1 = mysqli_query($conn, $sql1);
$row=$result1->fetch_assoc();
$id =$row["volunteeremailid"];

$vname = $pieces[0];

$sql2 = "SELECT * FROM rating WHERE `volunteerid` = '$id'";
$result2 = mysqli_query($conn, $sql2);
$row2 = $result2->fetch_assoc();

if($row2)
{
        $rat =$row2["rating"];
        $newrating = ($rating+$rat)/2;
		$sql3 = "UPDATE rating SET rating = '$newrating' WHERE volunteerid = '$id'";
}

else
{
	$sql3 = "INSERT INTO `rating` VALUES ('$id','$vname','$rating')";
}
if ($conn->query($sql3) === TRUE) 
{
	$conn->close();
   $response_data = array("status"=>200,"msg"=>"Your feedback was submitted!");
    echo json_encode($response_data);
} 
else
{
	$conn->close();
	$response_data = array("status"=>401,"msg"=>"Server down, try again later!");
    echo json_encode($response_data);
}
?>
