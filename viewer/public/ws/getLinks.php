<?php
    include("./include/include.php");
    header('Content-Type: application/json');
    $links=array();
    foreach ($listCat as $cat) {
        $links[]= array(
            'link' => $linkUrl.'autocertificazione',
            'text' => $cat->text,
        );
    }  
    $json_return=array(
        'status' => 'ok',
        'links' => $links,
    );
    print json_encode($json_return)
?>