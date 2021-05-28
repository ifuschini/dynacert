<?php
include("./include/include.php");
header('Content-Type: application/json');

$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "SELECT ID,name FROM `category` WHERE 1 ORDER BY ID";
$status = 'ok';
$response = array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[]=$row;
    }

} 
$json_response = array(
    'status' => $status,
    'response' => $response
);
print json_encode($json_response);


?>