<?php
include_once 'database_config.php';
$vemail = $_POST['vemail'];
$vname= $_POST['vname'];
$rating = $_POST['star2']; //name attr of div or of the input elemen

$conn=get_connection();


$sql2 = "SELECT * FROM rating WHERE `volunteerid` = '$vemail'";
$result2 = mysqli_query($conn, $sql2);
$row2 = $result2->fetch_assoc();

if($row2)
{
        $rat =$row2["rating"];
        $newrating = ($rating+$rat)/2;
	$sql3 = "UPDATE rating SET rating = '$newrating' WHERE volunteerid = '$vemail'";
}

else
{
	$sql3 = "INSERT INTO `rating` VALUES ('$vemail','$vname','$rating')";
}
if ($conn->query($sql3) === TRUE) 
{
	$conn->close();
   $response_data = array("status"=>200);
    echo json_encode($response_data);
} 
else
{
	$conn->close();
	$response_data = array("status"=>401);
    echo json_encode($response_data);
}
?>