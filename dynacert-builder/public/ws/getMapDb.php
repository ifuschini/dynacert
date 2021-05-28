<?php
include("./include/include.php");
header('Content-Type: application/json');
$filename= $repositoryMapDir.$_GET['formId'];
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "SELECT ID,map FROM `formMap` WHERE formId=" . $_GET['formId'];
$sql = "SELECT t1.map,t2.images FROM formMap t1, formPdfImage t2 WHERE t1.formId=". $_GET['formId']. " AND t2.formId=" . $_GET['formId'];
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
