<?php
include("./include/include.php");
include("./include/checksession.php");
header('Content-Type: application/json');
$cat=$_GET['cat'];
$date=$_GET['date'];
$filePath=$repositoryPath.$cat.'/'.$date."/_submit.txt";
$count=0;
$response=array();
if (file_exists($filePath)) {
    $file = fopen($filePath,"r");
    while(! feof($file))
    {
        $row=fgets($file);
        $count=$count + 1;
        $items=explode("\t", $row);
        if ($items[0] != NULL && $count > 1) {
        $response[]=array(
            "Giorno" => $items[0],
            "Ora" => $items[1],
            "Cognome" => $items[2],
            "Nome" => $items[3],
            "Documento" => $items[4],
        );
        }
    }
    fclose($file);
}
$json=array(
    "status" => 'ok',
    "certificate" => $response,
);
print json_encode($json);

?>