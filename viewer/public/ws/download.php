<?php
    include("./include/include.php");
    //if (!$_GET['frommail']) {
        include("./include/checksession.php");
    //}
    $pdfname=$_GET['name'];
    $file = $repositoryPath.$_GET['cat'].'/'.$_GET['date'].'/'.$pdfname;
    if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename='.basename($pdfname));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    } 
?>