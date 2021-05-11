<?php
include("./include/include.php");
header('Content-Type: application/json'); 
$file= $jsonPath.$idTemplate;
$fileConfigPdf=$jsonPath.$configPdf;
$content=file_get_contents($file);
$contentPdf = file_get_contents($fileConfigPdf);
$json=json_decode($content,true);
$jsonPdf = json_decode($contentPdf, true);
$json["costumer"]= $costumer;
$json["emailCostumer"]= $emailCostumer;
$json["pdfBack"]=$pdfBack;
$json["configPdf"] = $jsonPdf;
$json["expiredLicenseDate"] = isLicenseExpired($expiredLicenseDate);
print json_encode($json);
?>