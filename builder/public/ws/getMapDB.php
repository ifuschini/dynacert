<?php
include("./include/include.php");
header('Content-Type: application/json');
$filename= $repositoryMapDir.$_GET['surveyId'];
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "SELECT ID,map FROM `surveyMap` WHERE surveyId=" . $_GET['surveyId'];
$sql = "SELECT t1.map,t2.images FROM surveyMap t1, surveyPdfImage t2 WHERE t1.surveyId=". $_GET['surveyId']. " AND t2.surveyId=" . $_GET['surveyId'];
$status = 'ok';
$map = array();
$images=array();
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $map = $row['map'];
        $images = $row['images'];
    }
}
$jsonmap = array();
$imagesMap= array();
if (is_array($map) == FALSE) {
    $jsonmap = json_decode(json_decode($map));
} 
if (is_array($images) ==FALSE) {
    $imagesMap=json_decode(json_decode($images));  
}
$json_response = array(
    'status' => $status,
    'map' => $jsonmap,
    'images' => $imagesMap,
);
print json_encode($json_response);

print $json;

?>
