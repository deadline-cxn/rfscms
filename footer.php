<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

$data=$GLOBALS['data'];
if(empty($theme)) $theme=$data->theme;
if(empty($theme)) $theme=$GLOBALS['theme'];


echo "<p align=left><pre>";
sc_debugfooter(0);
echo "</pre></p>";

//$s=$GLOBALS['site_path'];
$tf="$RFS_SITE_PATH/themes/$theme/t.footer.php";

if(file_exists($tf)){
	include($tf);
}
else{

    echo "<BR><BR><BR>";


		if(empty($data->donated)){
				echo "<table border=0 width=100% ><tr><td align=center>";
				if(empty($data->donated))  {
					if(!empty($RFS_SITE_GOOGLE_ADSENSE)) 
						sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
				}
				else {
				 /// -- sc_info("Thanks for donating!","GREEN","BLACK");
				}
				echo "</td></tr></table>";
			}
		

    $dyear=date("Y");

    echo "<center></center>";
    // <a href=\"http://www.sethcoder.com/\">RFS CMS &copy;$dyear Seth Parson</a>
    for($i=0;$i<25;$i++) echo "<p> &nbsp; </p> <br> &nbsp; <br>";
    echo "</body></html>";
}

?>

