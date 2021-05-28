<?php
	session_start();
    header('Content-Type: application/json'); 
    setcookie("token", "", time()-3600,"/");
    session_destroy();
	$json=array (
		'status' => 'ok',
	);
		print json_encode($json);

?>