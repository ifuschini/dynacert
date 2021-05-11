<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$survey=$json->survey;
$surveyTitle=$json->surveyTitle;
$IDSurvey = $json->IDSurvey;
$categorySelected = $json->categorySelected;
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
if ($IDSurvey==NULL)
    $sql = "INSERT INTO `survey`(`title`, `config`,`category`) VALUES ('" . $surveyTitle . "','" . mysqli_real_escape_string($conn, json_encode($json)) . "',".$categorySelected.")";
else
    $sql = "UPDATE `survey` SET `config`= '" . mysqli_real_escape_string($conn, json_encode($json))."',title='". $surveyTitle . "',category=".$categorySelected." WHERE ID=" . $IDSurvey;
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
