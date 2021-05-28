<?php
include("./include/include.php");
header('Content-Type: application/json');
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$idForm=$_GET['id'];
$sql = "SELECT t1.map,t2.images,t3.title,t3.config FROM formMap t1, formPdfImage t2, form t3 WHERE t1.formId=" . $idForm ." AND t2.formId=" . $idForm . " AND t3.ID=" . $idForm;
$title = NULL;
$form = array();
$map = array();
$images = NULL;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $form = $row['config'];
        $map = $row['map'];
        $images = $row['images'];
    }
}
if (is_array($form) == TRUE && count($form)==0) {
    $sql = "SELECT title,config FROM `form` WHERE ID=" . $idForm;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $form = $row['config'];   
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
$formJson=json_decode($form);
$json=array(
    'form' => json_decode($formJson->form),
    'formTitle' => $title,
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