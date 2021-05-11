<?php
include("./include/include.php");
include("./include/checksession.php");
header('Content-Type: application/json');
 
$cat=$_GET['cat'];
$response = array();
if (file_exists($repositoryPath . $cat)) {
    $files = scandir($repositoryPath.$cat , 1);
    foreach ($files as $file) {
        if (substr($file,0,1)==2) {
            $year=substr($file,0,4);
            $month=substr($file,4,2);
            $day=substr($file,6,2);
            $response[]=array(
                'text' => $day.'/'.$month.'/'.$year,
                'value' => $file,
            );
        }
    }
}
$json=array(
    "status" => 'ok',
    "date" => $response,
);
print json_encode($json);
?>