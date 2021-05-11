<?php
include("./include/include.php");
date_default_timezone_set('Europe/Rome');
$today=date('Ymd');
$uploaddir = $repositoryPath.$_POST['cat'].'/'.$today;
$json=NULL;
$txt='';
$params=NULL;
if ($_POST['token'])
    $params= "secret=" . $reCaptchaSecret . "&response=" . $_POST['token'];
else
    $params = "secret=" . $reCaptchaSecretV3 . "&response=" . $_POST['tokenV3'];
if (isLicenseExpired($expiredLicenseDate)==TRUE) {
        $json=array (
                    'status' => 'ko',
                    'message' => 'license expired',
        );
        print json_encode($json);
        die;
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $reCapUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    $params
);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$jsonReCaptcha=json_decode($server_output);

curl_close($ch);

if ($jsonReCaptcha->success == false) {
        $json=array (
                    'status' => 'kor',
        );
        print json_encode($json);
        die;
}



if (!file_exists($repositoryPath.$_POST['cat'])) 
{
    if (array_search($_POST['cat'], array_column($listCat, 'value'))==0) {
        mkdir($repositoryPath.$_POST['cat']);
    } else {
        $json=array (
                    'status' => 'ko2',
        );
        print json_encode($json);
        die;
    }
}
if (!file_exists($uploaddir)) {
    mkdir($uploaddir);
    $txt = "Data\tOra\tCognome\tNome\tLuogo\tPDF\n";
}
$datehour=date('Ymd_H_i_s_');
//$nameFile=$datehour.$_POST['lastname']."_".$_POST['firstname'].".pdf";
$nameFile=round(microtime(true) * 1000).".pdf";
$uploadfile = $uploaddir.'/'.$nameFile;
$baseUrl=$hostUrl.$_POST['cat'].'/';
$url=$hostUrl.'?cat='.$_POST['cat'].'&date='.$today.'&frommail=true&name='.$nameFile;
$txt=$txt.date('d/m/Y')."\t".date('H:i:s')."\t".$_POST['lastname']."\t".$_POST['firstname']."\t".$nameFile;
$myfile = file_put_contents($uploaddir.'/_submit.txt', $txt.PHP_EOL , FILE_APPEND);
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    $json=array (
                'status' => 'ok',
    );
} else {
    $json=array (
                'status' => 'ko',
    );
}
print json_encode($json);

?>