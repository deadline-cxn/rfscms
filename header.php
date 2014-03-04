<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

// Switch to little header
if(isset($RFS_LITTLE_HEADER)) { if($RFS_LITTLE_HEADER==true) { include("lilheader.php"); return; } }

// check for config.php file
if(!file_exists("config/config.php")) { include("install/install.php"); exit(); }

// include all libraries (this will not output any text)
include_once("include/lib.all.php");

// check for site name definition
if(empty($RFS_SITE_NAME)) { include("install/install.php"); exit(); }

// Housekeeping
sc_maintenance();

// Display debug info 
lib_debug_header(0);

// Divert ajax requests
if(stristr($_REQUEST['action'],"sc_ajax_callback")) {
	include("include/lib.all.php");
	eval("$action();");
	exit();
}

// include theme definition file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.php")) include("$RFS_SITE_PATH/themes/$theme/t.php");
// include theme header file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.header.php")) include("$RFS_SITE_PATH/themes/$theme/t.header.php");
// otherwise use the default header (this file)
else {
	rfs_echo($RFS_SITE_DOC_TYPE);
	rfs_echo($RFS_SITE_HTML_OPEN);
	rfs_echo($RFS_SITE_HEAD_OPEN);    
	// get keywords from any search engine queries and put them in the seo output
	$keywords=$_GET['query'];
	if(empty($keywords)) $keywords=$_GET['q'];
	$keywords.=$RFS_SITE_SEO_KEYWORDS;	
	echo "<meta name=\"description\" 	content=\"$keywords\">";
	echo "<meta name=\"keywords\" 		content=\"$keywords\">";
	rfs_echo($RFS_SITE_TITLE);
	if(file_exists("$RFS_SITE_PATH/themes/$theme/t.css"))
		echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	echo "<link rel=\"canonical\" href=\"".sc_canonical_url()."\" />";
	rfs_echo($RFS_SITE_HEAD_CLOSE);	
	rfs_echo($RFS_SITE_BODY_OPEN);	
	
	if($_SESSION['admin_show_top']!="hide") {	
		
		echo "<table border=0 width=100% cellspacing=0 cellpadding=0 class=\"toptexttd\">";
		echo "<tr><td class=toptd align=left >";
		if ($RFS_THEME_TTF_TOP)  {
			$clr 	= sc_html2rgb($RFS_THEME_TTF_TOP_COLOR);
           $bclr	= sc_html2rgb($RFS_THEME_TTF_TOP_BGCOLOR);
			echo sc_image_text(
						$RFS_SITE_NAME,
						$RFS_THEME_TTF_TOP_FONT,						
						$RFS_THEME_TTF_TOP_FONT_SIZE,
						812,0,
						$RFS_THEME_TTF_TOP_FONT_X_OFFSET,
						$RFS_THEME_TTF_TOP_FONT_Y_OFFSET,
						$clr[0], $clr[1], $clr[2],
						$bclr[0], $bclr[1], $bclr[2],
						1,0 );
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
		echo "</td><td class=toptd> ";

		echo "<!-- $keywords --> ";
		echo "<font class=slogan>$RFS_SITE_SLOGAN</font>";
		echo "</td>";
		echo "<td class=toptd valign=bottom>";
		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.bot_right_corner.gif")) {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.bot_right_corner.gif\" align=right valign=bottom>";
			echo "</td><td class=logged_in_td>";
		}
		else
			echo " &nbsp; ";
		if($_SESSION["logged_in"]!="true")    {
			rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
			echo "</td><td class=logged_in_td>";
		}
		else    {
			echo "</td>";
			echo "<td class=logged_in_td>";
			rfs_echo($RFS_SITE_LOGGED_IN_CODE);
		}
		echo "</td>";		
		echo "</tr></table>";
		
		echo "<table border=0 width=100% class=sc_top_menu_table cellpadding=0 cellspacing=0>";
		echo "<tr class=sc_top_menu_table_td>";
		echo "<td class=sc_top_menu_table_td valign=top>";
		
		echo "<table border=0 cellpadding=0 cellspacing=0 class=sc_top_menu_table_td>";
		echo "<tr class=sc_top_menu_table_td>";
		             
		sc_menu_draw($RFS_THEME_MENU_TOP_LOCATION); 
		//echo "<td align=right class=sc_top_menu_table_td>";
		echo "<td class=sc_top_menu_table_inner class=contenttd>";
		sc_theme_form();		
		echo "</td>";
		echo "</tr></table>\n";
		//echo "</td></tr></table>";
		
		echo "<table border=0 width=100% class=sc_top_menu_table cellpadding=0 cellspacing=0 align=center>";
		echo "<tr><td align=center>";
		
		if(!sc_yes($data->donated)) {
			sc_donate_button();		
			sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
		
		}
		echo "</td></tr></table>";
		
		// echo "</td></tr></table>";
		
		echo "<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>";
		echo "<td class=lefttd valign=top>";
    	sc_draw_module("left");
		echo "</td>";		
		echo "<td valign=top class=midtd>";
		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.top_left_corner.gif"))    {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_left_corner.gif\" align=left>";
		}
		
	} else {
	}
}
//////////////////////////////////////////////
// Load javascripts

sc_ajax_javascript();
sc_javascript();

rfs_echo($RFS_SITE_JS_MSDROPDOWN_THEME);
rfs_echo($RFS_SITE_JS_JQUERY);
rfs_echo($RFS_SITE_JS_COLOR);
rfs_echo($RFS_SITE_JS_EDITAREA);

if(!stristr(sc_canonical_url(),"/net.php"))
rfs_echo($RFS_SITE_JS_MSDROPDOWN);

//////////////////////////////////////////////
// google analytics
sc_google_analytics();
//////////////////////////////////////////////
// count the page
sc_mcount($data->name);
//////////////////////////////////////////////
// system messages
sc_system_message();
//////////////////////////////////////////////
// do action
sc_do_action();
?>
