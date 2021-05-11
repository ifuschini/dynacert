<?php
include("./include/include.php");
header('Content-Type: application/json');
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "SELECT ID,title,config,category FROM `survey` WHERE ID=".$_GET['id'];
$status = 'ok';
$survey = array();
$title = null;
$category= null;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $survey = $row['config'];
        $category = $row['category'];
    }
}
$jsonSurvey=json_decode($survey);
$json_response = array(
    'status' => $status,
    'survey' => $jsonSurvey->survey,
    'title' => $title,
    'category' => $category,
);
print json_encode($json_response);



print $json;
