<?php
include("./include/include.php");
header('Content-Type: application/json');
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$idSurvey=$_GET['id'];
$sql = "SELECT title,config FROM `survey` WHERE ID=" . $idSurvey;
$sql = "SELECT t1.map,t2.images,t3.title,t3.config FROM surveyMap t1, surveyPdfImage t2, survey t3 WHERE t1.surveyId=" . $idSurvey ." AND t2.surveyId=" . $idSurvey . " AND t3.ID=" . $idSurvey;
$title = NULL;
$survey = array();
$map = array();
$images = NULL;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $survey = $row['config'];
        $map = $row['map'];
        $images = $row['images'];
    }
}
if (is_array($survey) == TRUE && count($survey)==0) {
    $sql = "SELECT title,config FROM `survey` WHERE ID=" . $idSurvey;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $survey = $row['config'];   
        }
    }
}
$jsonmap = array();
$imagesMap = array();
if (is_array($map) == FALSE) {
    $jsonmap = json_decode(json_decode($map));
}
if (is_array($images) == FALSE) {
    $imagesMap = json_decode(json_decode($images));
}
$surveyJson=json_decode($survey);
$json=array(
    'survey' => json_decode($surveyJson->survey),
    'surveyTitle' => $title,
    'costumer'=> $costumer,
    'emailCostumer'=> $emailCostumer,
    'pdfBack'=>$pdfBack,
    'images' => $images,
    'configPdf' => $jsonmap,
    'images' => $imagesMap,
    'expiredLicenseDate' => isLicenseExpired($expiredLicenseDate),
    'sql' => $sql,
);

print json_encode($json);
?>