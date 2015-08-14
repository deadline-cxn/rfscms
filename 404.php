<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
if(file_exists("_404.php")) {
	// include customized 404 file and exit 
	include("_404.php");
	exit();
}
else {

    echo "<html>\n<head>\n<title>RFS CMS 404</title>\n</head>\n";
    echo "<body style=' background-color: #000000; color: #ffffff; '>\n";
    echo "<p align=center> 404 Document not found... This is the custom RFS CMS page.</p>\n";
    echo "</html>\n";
}
?>