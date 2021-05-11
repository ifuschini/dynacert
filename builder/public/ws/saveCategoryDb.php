<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$category=$json->category;
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "INSERT INTO `category`(`name`) VALUES ('" . $category . "') LIMIT 1";
$status='ok';
$error=NULL;
if ($conn->query($sql) === TRUE) {
    $status='ok';
} else {
    $status='ko';
    $error=$conn->error;
}
$json_response = array(
    'status' => $status,
    'message' => $error,
    'sql' => $sql
);
print json_encode($json_response);

?>
