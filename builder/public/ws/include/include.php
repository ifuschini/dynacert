<?php
include('./confbuilder/conf.php');

function cors()
{

    header('Access-Control-Allow-Origin: *');

    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
}
function listdir_by_date($dir)
{
    $scan = scandir($dir);
    $tmp = [];
    foreach ($scan as $f) {
        if ($f == '.' || $f == '..') {
        } else {
            $tmp[basename($f)] = filemtime($dir . $f);
        }
    }
    arsort($tmp);
    return array_keys($tmp);
}
function cryptUser($user)
{
    $hash = $user;
    for ($i = 0; $i < 100000; $i++) {
        $hash = hash('sha256', $hash);
    }
    return $hash;
}

?>