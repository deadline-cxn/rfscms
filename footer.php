<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

include("include/lib.all.php");

$data=$GLOBALS['data'];
if(empty($theme)) $theme=$data->theme;
if(empty($theme)) $theme=$GLOBALS['theme'];

echo "<p align=left><pre>";
sc_debugfooter(0);
echo "</pre></p>";

$tf="$RFS_SITE_PATH/themes/$theme/t.footer.php";
if(file_exists($tf)){
	include($tf);
}
else{
    echo "<BR><BR><BR>";
	
	
		
	if(empty($data->donated)) {
		sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	}
	else {
		
	}
	
	echo "</td>";
		
	echo "<td class=\"righttd\" valign=top>";
	
		sc_draw_module("right");
	
	echo "</td></tr></table>";

    echo "<center>$RFS_SITE_COPYRIGHT</center>";
	for($i=0;$i<10;$i++) echo "<p> &nbsp; </p> <br> &nbsp; <br>";
    echo "</body></html>";
}

?>

