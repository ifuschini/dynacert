<?php
$ip = fgets(fopen('/var/www/conf/db_ip.txt', 'r'));
//this is ip only if you launch the script dynacert-server/configur_db_ip.sh
$dbhost = $ip;
$dbusr = "dynaadmin";
$dbpwd = "dynacert";
$dbname = "dynadb";
cors();
?>