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
    $reCaptchaSecret = '6LcRZgcaAAAAALZIQ0evjrnk-thz8MLRsr3GTjYR';
    $reCaptchaSecretV3 = '6LeaSDcaAAAAAA8rfJYWAkx86F_rCCvjrvL13-jJ';
    $expiredLicenseDate = "2022-02-01";
    //$idTemplate= 'VFJJQUdFIFBFUiBDT1ZJRDE5IElOIFBSRVNFTlpBIHByZXNzbyBGSVNBTCBGaXNpb3RlcmFwaWE=';
    $idTemplate= 'dHJpYWdlIHJlY292ZXJ5Mg==';
    $idSurvey= '8';
    $costumer= "FISAL S.r.l.";
    $emailCostumer= "studio@fisal.eu";
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