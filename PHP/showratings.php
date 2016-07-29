<?php
include_once 'database_config.php';
error_reporting(E_ERROR | E_PARSE);
$email = $_POST['email'];
$conn=get_connection();
$sql2 = "SELECT * FROM rating WHERE `volunteerid` = '$email'";
$result2 = mysqli_query($conn, $sql2);
$row2 = $result2->fetch_assoc();

if($row2)
{
        $rat = round($row2["rating"],2);
        $conn->close();
        $response_data = array("status"=>200,"rating"=>"Your Rating is: ".$rat."/5");
        echo json_encode($response_data);
} 
else
{
	$conn->close();
	$response_data = array("status"=>401,"rating"=>"No rating is available.");
    echo json_encode($response_data);
}
?>