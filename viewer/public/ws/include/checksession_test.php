<?php session_start(); ?>
<?php
    header('Content-Type: application/json'); 
        $result='ok';
        if ($_SESSION['timeout'] + $session_time * 60 < time()) {
                session_destroy();
                echo '{ "result": "ko", "service" : "checklogin"}';
        } else {
                echo '{ "result": "ok", "service" : "checklogin"}';                    
        }        
?>
