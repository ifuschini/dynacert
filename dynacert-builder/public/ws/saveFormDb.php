<?php
include("./include/include.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));
$form=$json->form;
$formTitle=$json->formTitle;
$IDForm = $json->IDForm;
$categorySelected = $json->categorySelected;
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
if ($IDForm==NULL)
    $sql = "INSERT INTO `form`(`title`, `config`,`category`) VALUES ('" . $formTitle . "','" . mysqli_real_escape_string($conn, json_encode($json)) . "',".$categorySelected.")";
else
    $sql = "UPDATE `form` SET `config`= '" . mysqli_real_escape_string($conn, json_encode($json))."',title='". $formTitle . "',category=".$categorySelected." WHERE ID=" . $IDForm;
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
