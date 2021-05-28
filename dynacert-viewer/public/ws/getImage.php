<?php
    include("./include/include.php");
    $filename=$_GET['name'];
    $type=$_GET['type'];
    if ($type=='image') {
        $file = $imagePath.$filename;
    } else if ($type == 'pdf') {
        $file = $pdfImagePath . $filename;
    } else {
        $file = $iconsPath.$filename;
    }
    if (file_exists($file)) {
        header('Content-Type: '.mime_content_type($file));
        ob_clean();
        flush();
        readfile($file);
    } 
?>