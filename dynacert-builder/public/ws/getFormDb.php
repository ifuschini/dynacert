<?php
include("./include/include.php");
header('Content-Type: application/json');
$conn = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);
$sql = "SELECT ID,title,config,category FROM `form` WHERE ID=".$_GET['id'];
$status = 'ok';
$form = array();
$title = null;
$category= null;
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['title'];
        $form = $row['config'];
        $category = $row['category'];
    }
}
$jsonForm=json_decode($form);
$json_response = array(
    'status' => $status,
    'form' => $jsonForm->form,
    'title' => $title,
    'category' => $category,
);
print json_encode($json_response);



print $json;
