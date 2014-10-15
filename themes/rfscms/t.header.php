<?

lib_rfs_echo($RFS_SITE_DOC_TYPE);
lib_rfs_echo($RFS_SITE_HTML_OPEN);
lib_rfs_echo($RFS_SITE_HEAD_OPEN);
lib_rfs_echo($RFS_SITE_META);
lib_rfs_echo($RFS_SITE_TITLE);

$RFS_SITE_THEME_CSS_URL="$RFS_SITE_URL/themes/$theme/t.css";

lib_rfs_echo($RFS_THEME_CSS);

lib_rfs_echo($RFS_SITE_HEAD_CLOSE);
lib_rfs_echo($RFS_SITE_BODY_OPEN);

echo "<div class=\"toptd\">";



	if ($RFS_THEME_TTF_TOP)  {
			$clr 	= lib_images_html2rgb($RFS_THEME_TTF_TOP_COLOR);
           $bclr	= lib_images_html2rgb($RFS_THEME_TTF_TOP_BGCOLOR);
		   echo "<div class=\"rfs_image_text_logo\">";
			echo lib_images_text(
						$RFS_SITE_NAME,
						$RFS_THEME_TTF_TOP_FONT,						
						$RFS_THEME_TTF_TOP_FONT_SIZE,
						952,30,
						$RFS_THEME_TTF_TOP_FONT_X_OFFSET,
						$RFS_THEME_TTF_TOP_FONT_Y_OFFSET,
						$clr[0], $clr[1], $clr[2],
						$bclr[0], $bclr[1], $bclr[2],
						1,0 );
						echo "</div>";
		}
		else {
			$base_srch="themes/$theme/t.top_image";
			$timg=0;
			if(file_exists("$RFS_SITE_PATH/$base_srch.jpg")) $timg=$base_srch.".jpg";
			if(file_exists("$RFS_SITE_PATH/$base_srch.gif")) $timg=$base_srch.".gif";
			if(file_exists("$RFS_SITE_PATH/$base_srch.png")) $timg=$base_srch.".png";
			if($timg) {
				echo "<img src=\"$RFS_SITE_URL/$timg\" align=\"left\" border=\"0\">";
			}
			else {
				echo "<div class=\"top_site_name\">$RFS_SITE_NAME</div>";
			}
		}

	if(lib_rfs_bool_true($RFS_SITE_SHOW_SLOGAN))
		if(!empty($RFS_SITE_SLOGAN))
                    echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
					
					
	echo "<div class=\"rfs_login_box\">";
	if($_SESSION['logged_in']) {
		lib_rfs_echo($RFS_SITE_LOGGED_IN_CODE);				
	}
	else {
		lib_rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
	}			
	echo "</div>";		
	
	echo "</div>";


	echo "<div style=\"height:3px;\"></div>";

	echo "<div class=\"rfs_top_menu_table\">";
	lib_menus_draw($RFS_THEME_MENU_TOP_LOCATION);
	lib_forms_theme_select();
if(!lib_rfs_bool_true($data->donated)) {
		lib_social_paypal();
		lib_social_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	}	
	echo "</div>";

	// echo "<div style=\"height:3px;\"></div>";

	//echo "<div class=\"thirdtd\">";	

	
           
echo "</div>";

echo "<div style=\"height:3px;\"></div>";

echo "<table width=100% cellpadding=0 cellspacing=0 border=0><tr>";
echo "<td class=\"lefttd\" valign=top>";
lib_modules_draw("left");
echo "</td>";
tco("midtd");

echo "<script>


</script>";



?>
