<?php
    include("./include/include.php");
    include("./include/checksession.php");
    $dir = $repositoryPath.$_GET['cat'].'/'.$_GET['date'];
    $zipname = $_GET['cat'].'_'.$_GET['date'].'.zip';
    $zipfile = $tmpDir.$zipname;
    $zip = new ZipArchive;
    $zip->open($zipfile, ZipArchive::CREATE);
    if ($handle = opendir($dir)) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
            $new_filename = substr($entry,strrpos($entry,'/') + 1);

            $zip->addFile($dir.'/'.$entry,$new_filename);
        }
      }
      closedir($handle);
    }
    $zip->close();

    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename='.basename($zipname));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zipfile));
    ob_clean();
    flush();
    readfile($zipfile);
    unlink($zipfile)
?>