<?php
// tempo di sessione espresso in minuti
    $ip = fgets(fopen('/var/www/conf/db_ip.txt', 'r'));
    //this is ip only if you launch the script dynacert-server/configur_db_ip.sh
    $dbhost = $ip;
    $dbusr = "dynaadmin";
    $dbpwd = "dynacert";
    $dbname = "dynadb";
    cors();
    $session_time = 10000;
    $tokenLength=32;
    $reCapUrl = "https://www.google.com/recaptcha/api/siteverify";
    $reCaptchaSecretV3 = '<secret key>';
    $expiredLicenseDate = "2022-02-01";
    $costumer= "";
    $emailCostumer= "";
    $configPdf='config.json';
    $imagePath='./confdyna/images/';
    $iconsPath = './confdyna/icons/';
    $jsonPath = './confdyna/pdfJson/';
    $pdfImagePath = './confdyna/pdfImages/';
    $repositoryPath='../../../dynarepository/';
    $tmpDir='../../../tmp/';
    $hostUrl='https://'.$_SERVER['HTTP_HOST'].'/ws/download.php';
    $admUrl='https://'.$_SERVER['HTTP_HOST'].'/#/adm/main';
    $linkUrl='https://'.$_SERVER['HTTP_HOST'].'/#/';

$listCat = json_decode('
    [
        {
            "text": "All",
            "value": "all"
        }
    ]
    '); ?>