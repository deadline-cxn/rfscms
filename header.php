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
    echo "<head  prefix=\"og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# books: http://ogp.me/ns/books#\">\n";
echo "

<meta property=\"fb:app_id\" content=\"$RFS_SITE_FACEBOOK_APP_ID\">
<meta property=\"fb:admins\" content=\"seth.parson\">

<meta property='og:url'    content=\"$RFS_SITE_URL\" />
<meta property=\"og:site_name\" content=\"$RFS_SITE_NAME\"/><br>

<meta property=\"og:type\"    content=\"sethcoder\" />
<meta property=\"og:title\"  content=\"$RFS_SITE_URL\" />
				
"; // $RFS_SITE_FACEBOOK_ADMINS\">	
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

	sc_div("TITLE");
	rfs_echo($RFS_SITE_TITLE);

	sc_div("THEME CSS");
	echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	echo "<link rel=\"canonical\" href=\"$RFS_SITE_URL".$_SERVER['PHP_SELF']."\" />";

	sc_div("\$RFS_SITE_JS_JQUERY_UI_CSS");
	rfs_echo($RFS_SITE_JS_JQUERY_UI_CSS);
	

	sc_div("head");
    echo "</head>\n";
	sc_div("body");
    echo "<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n";

	sc_div("\$RFS_SITE_JS_JQUERY");
	rfs_echo($RFS_SITE_JS_JQUERY);
	sc_div("\$RFS_SITE_JS_JQUERY_UI");
	rfs_echo($RFS_SITE_JS_JQUERY_UI);

	sc_div("OTHER HEADER STUFF...");
	
	if($_SESSION['admin_show_top']!="hide") {	

		echo "<table border=0 width=100% height=50 cellspacing=0 cellpadding=0 class=toptd>";
		echo "<tr><td class=toptd align=left width=80%>";

		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.top_image.gif"))
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_image.gif\" align=left>";
		else{
                $clr = sc_html2rgb($RFS_SITE_NAV_FONT_COLOR);
                $bclr= sc_html2rgb($RFS_SITE_NAV_FONT_BGCOLOR);

                echo sc_image_text(

							$RFS_SITE_NAME,

							$RFS_SITE_NAV_FONT,
							40,
							812,84,
							0, -10,
							$clr[0], $clr[1], $clr[2],
							$bclr[0], $bclr[1], $bclr[2],
							1,1 );
		}

		echo "<font class=toptd>$keywords</font> ";
		echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
		echo "<td class=toptd valign=bottom>";
		
		if(file_exists("$RFS_SITE_PATH/themes/$theme/t.bot_right_corner.gif"))    {
			echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.bot_right_corner.gif\" align=right valign=bottom>";
			echo "</td><td class=logged_in_td>";
		}
		else
			echo "&nbsp;";
		if($_SESSION["logged_in"]!="true")    {
			rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
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
		echo "<table border=0 cellpadding=8 cellspacing=0 class=sc_top_menu_table>";
		echo "<tr class=sc_top_menu_table>";

		sc_menu_draw($RFS_SITE_TOP_MENU_LOCATION);

		echo "<td class=sc_top_menu_table width=98%>&nbsp;</td>";
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

echo "<script type=\"text/javascript\" src=\"$RFS_SITE_URL/3rdparty/jscolor/jscolor.js\">";

sc_google_analytics();
sc_mcount($data->name);

// Automatic action function
$px=explode("/",$_SERVER['PHP_SELF']);
$pout=str_replace(".php","",$px[count($px)-1]);
$_thisfunk=$pout."_action_$action";
$_thisfunk=str_replace(" ","_",$_thisfunk);

d_echo($_thisfunk);

$ecode=" if( function_exists(\"$_thisfunk\") == true) {
        $_thisfunk();
} else {
    if(\$_SESSION[\"debug_msgs\"]==true)
	sc_info(\"DEBUG >> WARNING: MISSING $_thisfunk(); \",\"WHITE\",\"BLUE\");
}";


d_echo($ecode);
eval($ecode);

?>