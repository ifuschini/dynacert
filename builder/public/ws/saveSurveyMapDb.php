<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
//SAVE MAP
$sql = "SELECT ID,map FROM `surveyMap` WHERE surveyId=" . $json->surveyId;
$status = 'ok';
$map = array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE `surveyMap` SET `map`= '" . mysqli_real_escape_string($conn, json_encode($json->map)) . "' WHERE surveyId=" . $json->surveyId;
} else {
    $sql = "INSERT INTO `surveyMap`(`surveyId`, `map`) VALUES (" . $json->surveyId . ",'" . mysqli_real_escape_string($conn, json_encode($json->map)) . "')";
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
$sql = "SELECT ID FROM `surveyPdfImage` WHERE surveyId=" . $json->surveyId;
$status = 'ok';
$map = array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql = "UPDATE `surveyPdfImage` SET `images`= '" . mysqli_real_escape_string($conn, json_encode($json->images)) . "' WHERE surveyId=" . $json->surveyId;
} else {
    $sql = "INSERT INTO `surveyPdfImage`(`surveyId`, `images`) VALUES (" . $json->surveyId . ",'" . mysqli_real_escape_string($conn, json_encode($json->images)) . "')";
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
