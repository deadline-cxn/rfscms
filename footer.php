<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// include("include/lib.all.php");
// $data=$GLOBALS['data']; if(empty($theme)) $theme=$data->theme; if(empty($theme)) $theme=$GLOBALS['theme'];
// output some debug information

lib_debug_footer(0);
// include custom theme footer instead of this one
if(file_exists("$RFS_SITE_PATH/themes/$theme/t.footer.php")){
	include("$RFS_SITE_PATH/themes/$theme/t.footer.php");
}
else{
		
	if(isset($RFS_LITTLE_HEADER)) {
		if($RFS_LITTLE_HEADER==true) {
			
			echo "<p>&nbsp;</p>";
			echo "<p>&nbsp;</p>";
			echo "<p>&nbsp;</p>";
			
			lib_rfs_echo($RFS_SITE_BODY_CLOSE);
			lib_rfs_echo($RFS_SITE_HTML_CLOSE);
			return;
		}
	}
	
    echo "<BR><BR><BR>";
		
	if(empty($data->donated)) {
		sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	}
	else {
		
	}
	echo "</td>"; // END MIDTD	
	
	////////////////////////////////////////////////////////////////////
	// DRAW THE RIGHT MODULES (RIGHTTD)
	echo "<td class=\"righttd\" valign=top>";
	lib_modules_draw("right");	
	echo "</td>"; // END RIGHTTD
	
	echo "</tr></table>"; // FINISH PAGE TABLE

	// PUT COPYRIGHT INFORMATION
	echo "<div class=\"copyright\">";
	echo $RFS_SITE_COPYRIGHT;	
	echo "</div>";
	
	// END THE PAGE
	lib_rfs_echo($RFS_SITE_BODY_CLOSE);
	lib_rfs_echo($RFS_SITE_HTML_CLOSE);
}

?>

