<?php
include('./confdyna/conf.php');
function cors()
{

    header('Access-Control-Allow-Origin: *');

    header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
}
function isLicenseExpired($dateexpired) {
    $today = date('y-m-j');
    $diff = strtotime($dateexpired) - strtotime($today);
    return $diff < 0;
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