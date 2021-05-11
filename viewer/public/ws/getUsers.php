<?php
include("./include/include.php");
include("./include/checksession.php");
header('Content-Type: application/json');
$usersObj=array();
if ($_SESSION['usersession'] == 'admin') {
        
    foreach ($users as $key => $value) {
        $usersObj[]=array (
            'category' =>$value->category,
            'username' =>$key,
        );
    }
}
usort($usersObj, function ($item1, $item2) {
    return $item1['username'] <=> $item2['username'];
});
$json=array(
        "status" => 'ok',
        "users" => $usersObj,
        "categories" => $listCat,
); 
print json_encode($json);
?>