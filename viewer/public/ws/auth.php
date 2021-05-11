<?php
	include('./include/include.php');
	session_start();

	 header('Content-Type: application/json'); 
        $json=json_decode( file_get_contents('php://input'));
		$username = $json->username;
		$displayName=$username;
		$password = $json->password;
        $hash=cryptUser($password);
        $login='ko';
        $message='';
		for ( $i = 0; $i < 100000; $i ++ ) {
  				$hash = hash('sha256', $hash);
		}
		if($users->$username->password == $hash)
		{
			$_SESSION['timeout'] = time();
			$_SESSION['usersession'] = $username;
			$_SESSION['category'] = $users->$username->category;
			$token = bin2hex(random_bytes($tokenLength));
			$_SESSION['token'] = $token;
			setcookie("token",  $token, time()+3600,'/');  /* expire in 1 hour */

			$login="ok";
		} else {	
			$message = "Username/Password errato";
        }
	$json=array (
		'status' => $login,
		'message' => $message,
	);
		print json_encode($json);
?>