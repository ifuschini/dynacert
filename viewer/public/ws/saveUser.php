<?php
include("./include/include.php");
include("./include/checksession.php");
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'));

$categories = $json->categories;
$username = $json->username;
$password = $json->password;
$isNewUser = $json->isNewUser;
$isDelete = $json->isDelete;
$today = date('Ymd_hs');
$backupFile = '../../../conf/' . $_SERVER['HTTP_HOST'] . '/backup/users.json.' . $today;


$categoryToSave='';
$json_response = array(
    'status' => 'ok',
);

foreach ($categories as $category) {
    $categoryToSave= $categoryToSave.$category.',';
}
$categoryToSave=substr($categoryToSave,0,strlen($categoryToSave) -1);
$hash=null;
if ($password != NULL) {
    $hash = cryptUser($password);
    $login = 'ko';
    $message = '';
    for ($i = 0; $i < 100000; $i++) {
        $hash = hash('sha256', $hash);
    }
}
if ($isDelete == TRUE) {
    if (($users->$username) && $users->$username != 'admin') {
        unset($users->$username);
        copy($fileUsers, $backupFile);
        file_put_contents($fileUsers, json_encode($users));
    } else {
        $json_response = array(
            'status' => 'ko',
        );
    }    
} else if ($isNewUser==FALSE) {
    if ($users->$username) {
        $users->$username->category=$categoryToSave;
        if ($hash != NULL) {
            $users->$username->password=$hash;
        }
        copy($fileUsers, $backupFile);
        file_put_contents($fileUsers, json_encode($users));
    } else {
        $json_response = array(
            'status' => 'ko',
        );
    }
} else if ($isNewUser == TRUE) {
    if ($users->$username) {
        $json_response = array(
            'status' => 'ko1',
        );
    } else {
        $users->$username=array(
            'category' => $categoryToSave,
            'password' => $hash,
        );
        copy($fileUsers, $backupFile);
        file_put_contents($fileUsers, json_encode($users));

    }
} 
print json_encode($json_response)
?>