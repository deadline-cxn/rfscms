<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// All of these variables should be integrated into the
// administration panel so you shouldn't need to
// manually change this file.
// That being said, the system will fall back to the 
// information stored here in the case that it can not
// find it in the database.
// I recommend that you back up this file BEFORE you edit
// anything. If you completely mess up the file,
// you can always download a new one from github
//
// https://github.com/sethcoder/rfscms
//
/////////////////////////////////////////////////////////////////////////////////////////
// SYSTEM STUFF
$RFS_SITE_OS="X";
$RFS_SITE_PATH_SEP="/";
if(substr(PHP_OS,0,3)=="WIN") {
	$RFS_SITE_PATH_SEP="\\";
	$RFS_SITE_OS="Windows";
}
$RFS_SITE_SESSION_ID        = "RFS_CMS_";
if(isset($_SESSION))
$RFS_SITE_SESSION_USER      = $_SESSION['valid_user'];
$RFS_SITE_ADMIN             = "Administrator";
if(isset($SERVER))
$RFS_SITE_ADMIN_EMAIL       = "admin@".$SERVER['DOCUMENT_ROOT'];
$RFS_SITE_SLOGAN            = " (SITE VAR: SLOGAN) Powered by <a href=\"http://www.sethcoder.com/modules/wiki/rfswiki.php?name=RFS+Content+Management+System\">RFSCMS</a>";
if(isset($SERVER))
$RFS_SITE_URL               = $SERVER['DOCUMENT_ROOT'];
else
$RFS_SITE_URL				= " ";
$RFS_SITE_PATH              = getcwd();
$RFS_SITE_ERROR_LOG         = "/var/log/apache2/error.log";
/////////////////////////////////////////////////////////////////////////////////////////
// TAGS  (Experimental)
$RFS_TAGS=array(
"RFS_TAG_FUNCTION" 			=> "RFS_TAG_FUNCTION",
"RFS_TAG_PHP_SELF"			=> "RFS_TAG_PHP_SELF",
"RFS_TAG_CANONICAL"			=> "RFS_TAG_CANONICAL",
"lib_social_facebook_login_r" 		=> "<!--RTAG_FACEBOOK_LOGIN-->",
"lib_buttons"				=> "<!--RTAG_BUTTON"
);
/////////////////////////////////////////////////////////////////////////////////////////
// WIKI STUFF
$RFS_SITE_WIKI_IMAGES_PATH  = "images/wiki";
$rfsw_header        			= "header.php"; 
$rfsw_footer        			= "footer.php";
$rfsw_dbname        			= "";   			// change these
$rfsw_address       			= "";  			// variables
$rfsw_user          			= "";   			// to work with
$rfsw_pass          			= "";           	// your database
$rfsw_img_path      			= "images/wiki"; 	// path to image uploads
$rfsw_bullet_image  			= "$rfsw_img_path/bullet.gif";
$rfsw_admin_mode    			= "false";
/////////////////////////////////////////////////////////////////////////////////////////
// FONT STUFF
$RFS_SITE_NAV_FONT          = "impact.ttf";
$RFS_SITE_NAV_IMG           = "0";
/////////////////////////////////////////////////////////////////////////////////////////
// THEME STUFF
$RFS_SITE_DEFAULT_THEME     = "default";
$RFS_SITE_FORCE_THEME       = true;
$RFS_SITE_FORCED_THEME      = "default";
$RFS_SITE_THEME_CSS_URL     = "";
$RFS_SITE_THEME_DROPDOWN    = false;
/////////////////////////////////////////////////////////////////////////////////////////
// SOCIAL NETWORKING STUFF
$RFS_SITE_ADDTHIS_ACCT		= ""; // www.addthis.com
/////////////////////////////////////////////////////////////////////////////////////////
// Facebook integration
$RFS_SITE_FACEBOOK_APP_ID   = "";
$RFS_SITE_FACEBOOK_SECRET   = "";
$RFS_SITE_FACEBOOK_SDK      = "$RFS_SITE_PATH/facebook/src/facebook.php";
/////////////////////////////////////////////////////////////////////////////////////////
// FILES STUFF
$RFS_SITE_ALLOW_FREE_DOWNLOADS = "false";
/////////////////////////////////////////////////////////////////////////////////////////
$RFS_THEME_CSS               = "<LINK rel=\"stylesheet\" href=\"\$RFS_SITE_THEME_CSS_URL\" type=\"text/css\">\n";
$RFS_THEME_MENU_TOP_LOCATION = "top"; 
$RFS_THEME_MENU_LEFT_LOCATION= "left";
$RFS_SITE_FOOTER            = "";
$RFS_SITE_COPYRIGHT         = "<a href=\"http://www.sethcoder.com/modules/wiki/rfswiki.php?name=RFS+Content+Management+System\">Made with RFSCMS</a>";
$RFS_SITE_MENU_TOP_LOCATION = "top";

$RFS_SITE_JOIN_FORM_CODE		= "
<p>Your information will not be shared with anyone.</p>
<table border=0 cellspacing=0 cellpadding=0>
<form method=post action=\"$RFS_SITE_URL/login.php\">
<input type=hidden name=action value=join_go>
<tr><td> User ID </td><td><input type=textbox  name=userid value=\"\">  </td></tr>
<tr><td> Email   </td><td><input type=textbox  name=email value=\"\">    </td></tr>
<tr><td>         </td><td> </td></tr>
<tr><td>         </td><td><input type=\"submit\" name=\"Register\" value=\"Register\"></td></tr>
</form></table>\n";

$RFS_SITE_LOGIN_FORM_CODE   = " 
<script src=\"\$RFS_SITE_URL/include/md5.js\"> </script>
<form method=post action=\"\$RFS_SITE_URL/login.php\">
<table align=right border=0 cellspacing=0 cellwidth=0 cellpadding=0 valign=middle>\n

<tr valign=middle>\n
<td align=right class=login><font class=slogan>Login</font><input type=hidden name=outpage value=\"\$thispage\"><input type=hidden name=action value=\"logingo\"></td>
<td class=login><input type=text name=userid size=10 class=\"b4text\"></td>
<td> <!--RTAG_FACEBOOK_LOGIN--> </td>
</tr>

<tr>\n
<td align=right class=login><font class=slogan>Password</font></td>
<td class=login><input type=password name=password size=10 class=\"b4text\"></td>\n
<input type=hidden name=outpage value='RFS_TAG_CANONICAL'>\n
<input type=hidden name=login value=fo_shnizzle>\n
<td valign=middle>\n <input type=\"submit\" name=\"Login\" value=\"Login\">\n</td>
</tr>

<tr>
<td></td>
<td></td>
<td> &nbsp;<a href=\$RFS_SITE_URL/login.php?action=join&outpage=\$PHP_SELF>Register</a>
</td>
</tr>
</table>
</form>
";

$RFS_SITE_LOGGED_IN_CODE   = "<div class=logged_in_box>\$RFS_SITE_SESSION_USER (<a href=\$RFS_SITE_URL/login.php?action=logout>logout</a>)</div>";
/////////////////////////////////////////////////////////////////////////////////////////
// 3rd Party Files
// java script locations
$RFS_SITE_JS_JQUERY         = "<script src=\"\$RFS_SITE_URL/3rdparty/jquery/jquery.js\"></script>";
$RFS_SITE_JS_COLOR          = "<script src=\"\$RFS_SITE_URL/3rdparty/jscolor/jscolor.js\"></script>";
$RFS_SITE_JS_MOOTOOLS       = "<script src=\"\$RFS_SITE_URL/3rdparty/mootools/mootools.js\"></script>";
$RFS_SITE_JS_EDITAREA       = "<script src=\"\$RFS_SITE_URL/3rdparty/editarea/edit_area/edit_area_full.js\"></script>";
$RFS_SITE_JS_MSDROPDOWN 		= "<script src=\"\$RFS_SITE_URL/3rdparty/ms-dropdown/js/msdropdown/jquery.dd.min.js\"></script> <script language=\"javascript\"> $(document).ready(function(e) { try { $(\"body select\").msDropDown(); } catch(e) { alert(e.message);} }); </script> ";
$RFS_SITE_JS_MSDROPDOWN_THEME = "<link rel=\"stylesheet\" type=\"text/css\" href=\"\$RFS_SITE_URL/3rdparty/ms-dropdown/css/msdropdown/dd.css\" />  <link rel=\"stylesheet\" type=\"text/css\" href=\"\$RFS_SITE_URL/3rdparty/ms-dropdown/css/msdropdown/skin2.css\" /> ";
/////////////////////////////////////////////////////////////////////////////////////////
// Figure out what to put in the title...
// If you're not sure what to put, just leave it alone
// 
// TODO: Move this somewhere else
//
/* 	@include_once("../lib.news.php");
	@include_once("lib.news.php");
        $title=$_GLOBALS['site_name'];
        if($_SERVER['PHP_SELF']==$_GLOBALS['site_url'].'/index.php')
            $title=rfs_get_news_headline(rfs_get_top_news_id());
        if(!empty($description)) $title=$description;
        if(!empty($name)) $title=$name;
        if(!empty($sname)) $title=$sname;
            if(stristr( $_SERVER['PHP_SELF'],"pics.php")) {
                $title="Pictures";
                if(!empty($id)) {
                $p=lib_mysql_fetch_one_object("select * from pictures where id='$id'");
                if(!empty($p->sname)){
                    $title=$p->sname;
                }
                else {
                    $c=lib_mysql_fetch_one_object("select * from categories where id='$p->category'");
                    if(!empty($c->name)) {
                        $title=$c->name;
                    }
                }
            }
        }

        if(!empty($_GET['nid']))
            $title=rfs_get_news_headline($_GET['nid']);
        if(!empty($what)) $title=$what;
*/
if(!isset($title)) $title=" ";
$RFS_SITE_TITLE         ="<TITLE> \$RFS_SITE_NAME $title </TITLE>";
/////////////////////////////////////////////////////////////////////////////////////////
// KEYWORDS
$RFS_SITE_SEO_KEYWORDS = $title."";
/////////////////////////////////////////////////////////////////////////////////////////
// Unlikely to need changes
$RFS_SITE_DOC_TYPE          = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
$RFS_SITE_HTML_OPEN         = "<HTML>\n";
$RFS_SITE_HEAD_OPEN         = "<HEAD>\n
                               <META NAME=\"ROBOTS\" CONTENT=\"INDEX,FOLLOW\">
                               <META http-equiv=\"Content-Language\" content=\"en-us\">
                               <META http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
                               <META name=\"GENERATOR\" content=\"RFS CMS\">
                               <META name=\"ProgId\" content=\"RFS CMS\">\n";
$RFS_SITE_HEAD_CLOSE        = "</HEAD>\n";
$RFS_SITE_BODY_OPEN         = "<BODY>\n";// topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n\n";
$RFS_SITE_BODY_CLOSE        = "</BODY>\n";
$RFS_SITE_HTML_CLOSE        = "</HTML>\n";
$RFS_SITE_DELIMITER         = "¥";
/////////////////////////////////////////////////////////////////////////////////////////
// This file can not have any trailing spaces
?>
