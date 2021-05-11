<?php
include("./include/include.php");
echo $dbhost;
$con = mysqli_connect($dbhost, $dbusr, $dbpwd, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die;
}
echo "connected";

?>