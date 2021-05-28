<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
//SAVE MAP
$sql = "SELECT ID,map FROM `formMap` WHERE formId=" . $json->formId;
$status = 'ok';
$map = array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE `formMap` SET `map`= '" . mysqli_real_escape_string($conn, json_encode($json->map)) . "' WHERE formId=" . $json->formId;
} else {
    $sql = "INSERT INTO `formMap`(`formId`, `map`) VALUES (" . $json->formId . ",'" . mysqli_real_escape_string($conn, json_encode($json->map)) . "')";
}
$status = 'ok';
$error = NULL;
if ($conn->query($sql) === TRUE) {
    $status = 'ok';
} else {
    $status = 'ko';
    $error = $conn->error;
}
//SAVE IMAGES
$sql = "SELECT ID FROM `formPdfImage` WHERE formId=" . $json->formId;
$status = 'ok';
$map = array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE `formPdfImage` SET `images`= '" . mysqli_real_escape_string($conn, json_encode($json->images)) . "' WHERE formId=" . $json->formId;
} else {
    $sql = "INSERT INTO `formPdfImage`(`formId`, `images`) VALUES (" . $json->formId . ",'" . mysqli_real_escape_string($conn, json_encode($json->images)) . "')";
}
$status = 'ok';
$error = NULL;
if ($conn->query($sql) === TRUE) {
    $status = 'ok';
} else {
    $status = 'ko';
    $error = $conn->error;
}
$json_response = array(
    'status' => $status,
    'message' => $error,
    'sql' => $sql
);
print json_encode($json_response);

?>
