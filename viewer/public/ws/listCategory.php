<?php
include("./include/include.php");
include("./include/checksession.php");
$category=[];
if ($_SESSION['category']=='all') {
    $category = $listCat;
} else {
    foreach ($listCat as $i) {
        $categoryToCheck=explode(",",$_SESSION['category']);
        foreach ($categoryToCheck as $cat) {
            if ($i->value == $cat) {
                $category[]=$i;
            }
        }
    }
}

$json=array(
    "status" => 'ok',
    "category" => $category,
    "licenseExpired" => isLicenseExpired($expiredLicenseDate),
);
print json_encode($json);
?>