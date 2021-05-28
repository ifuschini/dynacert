<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$id=$json->id;
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = 'DELETE FROM category where id='.$id;
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
