<?php
// tempo di sessione espresso in minuti
    $ip = fgets(fopen('/var/www/conf/db_ip.txt', 'r'));
    //this is ip only if you launch the script dynacert-server/configur_db_ip.sh
    $dbhost = $ip;
    $dbusr = "dynaadmin";
    $dbpwd = "dynacert";
    $dbname = "dynadb";
    cors();
    $tokenLength=32;
    $reCapUrl = "https://www.google.com/recaptcha/api/siteverify";
    $reCaptchaSecretV3 = '<secret key>';
    $expiredLicenseDate = "2022-02-01";
?>