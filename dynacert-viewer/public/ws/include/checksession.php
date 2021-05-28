<?php
        session_start();
        header('Content-Type: application/json'); 
        $result='ok';
        if ($_SESSION['timeout'] + $session_time * 60 < time() || isset($_COOKIE['token']) == false || $_COOKIE['token'] != $_SESSION['token']) {
                setcookie("token", "", time()-3600,"/");
                session_destroy();
                echo '{ "status": "ko", "service" : "checklogin"}';
                die;
        } else {
                $_SESSION['timeout']=time();
                    
        }        
?>
