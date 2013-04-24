<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2013 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(!file_exists("config/config.php")) { include("install/install.php"); exit(); }
include_once("include/lib.all.php");

sc_div(__FILE__); 
sc_div(" Really Frickin Simple Content Management System $RFS_VERSION http://www.sethcoder.com/ ");

sc_maintenance();
sc_debugheader(0);

if( file_exists("$RFS_SITE_PATH/themes/$theme/t.php"))
        include("$RFS_SITE_PATH/themes/$theme/t.php");
		
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.header.php")) {
        include("$RFS_SITE_PATH/themes/$theme/t.header.php");
} else {
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<META NAME=\"ROBOTS\" CONTENT=\"INDEX,FOLLOW\">";
    echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">";
	echo "<meta name=\"GENERATOR\" content=\"Notepad\">";
	echo "<meta name=\"ProgId\" content=\"Notepad\">";
	$keywords=$_GET['query'];    if(empty($keywords))
	$keywords=$_GET['q'];        if(empty($keywords))
	$keywords=$RFS_SITE_KEYWORDS;
	echo "<meta name=\"description\" content=\"$keywords\">";
	echo "<meta name=\"keywords\" content=\"$keywords\">";

	rfs_echo($RFS_SITE_TITLE);

	echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	echo "<link rel=\"canonical\" href=\"".sc_canonical_url()."\" />";
    echo "</head>\n";
    echo "<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n";
	
	if($_SESSION['admin_show_top']!="hide") {	

		echo "<table border=0 width=100% cellspacing=0 cellpadding=0 class=toptd>";
		echo "<tr><td class=toptd align=left >";

		if ($RFS_SITE_TTF_TOP)  {
			$clr 	= sc_html2rgb($RFS_SITE_TTF_TOP_COLOR);
           $bclr	= sc_html2rgb($RFS_SITE_TTF_TOP_BGCOLOR);

			echo sc_image_text(
						$RFS_SITE_NAME,
						$RFS_SITE_TTF_TOP_FONT,
						
						$RFS_SITE_TTF_TOP_FONT_SIZE,
						812,0,
						0   + $RFS_SITE_TTF_TOP_FONT_X_OFFSET,
						-15 + $RFS_SITE_TTF_TOP_FONT_Y_OFFSET,
						$clr[0], $clr[1], $clr[2],
						$bclr[0], $bclr[1], $bclr[2],
						1,0 );
		}else {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_image.gif\" align=\"left\" border=\"0\">";
		}
		echo "</td><td class=toptd> ";

		echo "<font class=toptd>$keywords</font> ";
		echo "<font class=slogan>$RFS_SITE_SLOGAN</font>";
		echo "</td>";
		echo "<td class=toptd valign=bottom>";
		
		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.bot_right_corner.gif"))    {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.bot_right_corner.gif\" align=right valign=bottom>";
			echo "</td><td class=logged_in_td>";
		}
		else
			echo " &nbsp; ";
		if($_SESSION["logged_in"]!="true")    {
			rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
			echo "</td><td class=logged_in_td>";
			sc_facebook_login();
		}
		else    {
			echo "</td>";
			echo "<td class=logged_in_td>";
			rfs_echo($RFS_SITE_LOGGED_IN_CODE);
		}
		echo "</td>";
		
		echo "</tr></table>";
		echo "<table border=0 width=100% class=sc_top_menu_table cellpadding=0 cellspacing=0><tr class=sc_top_menu_table>";
		echo "<td class=sc_top_menu_table valign=top>";
		echo "<table border=0 cellpadding=0 cellspacing=0 class=sc_top_menu_table>";
		echo "<tr class=sc_top_menu_table>";

		sc_menu_draw($RFS_SITE_TOP_MENU_LOCATION);

		//echo "<td class=sc_top_menu_table width=98% >&nbsp;</td>";
		echo "<td align=right class=sc_top_menu_table >";
		echo "<table border=0 cellspacing=0 cellpadding=0><tr>\n";
		echo "<td class=sc_top_menu_table class=contenttd>";

		sc_theme_form();

		echo "</td></tr></table>\n";
		echo "</td></tr></table>";
		echo "</td></tr></table>";
		echo "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>";
		
		echo "<td class=lefttd valign=top width=200>";
		echo "<table border=0 cellpadding=5 cellspacing=0 width=100% ><tr><td valign=top class=lefttd >";

    	sc_draw_module("left");

		echo "</td></tr></table>";
		echo "</td>";
		
		echo "<td valign=top class=midtd>";
		echo "<table border=0 cellpadding=0 cellspacing=0 width=100% >";
		echo "<tr><td valign=top class=midtd width=41>";
		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.top_left_corner.gif"))    {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_left_corner.gif\">";
			echo "</td><td>";
		}
		else
			echo "&nbsp;";
		echo "</td></tr>";
		echo "<tr><td width=80% >";
		
	} else {
	}
}

//////////////////////////////////////////////
// Load javascripts

rfs_echo($RFS_SITE_JS_JQUERY_UI_CSS);
rfs_echo($RFS_SITE_JS_JQUERY);
rfs_echo($RFS_SITE_JS_JQUERY_UI);
rfs_echo($RFS_SITE_JS_COLOR);
rfs_echo($RFS_SITE_JS_EDITAREA);

//////////////////////////////////////////////
// google analytics
sc_google_analytics();

//////////////////////////////////////////////
// count the page
sc_mcount($data->name);

//////////////////////////////////////////////
// do action
sc_do_action();
?>
