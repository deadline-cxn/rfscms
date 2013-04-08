<?
//////////////////////////////////////////////////////////
// You should edit the variables in this file to 
// customize your website.
// All of these variables should be integrated into the
// administration panel so you shouldn't need to 
// manually change this file.
// The order in which some variables are defined
// is important.. Be careful!
// Items that you should most definately change are 
// noted with * beside them
//
// I recommend that you back up this file BEFORE you edit
// anything. If you are get it completely messed up
// you can always download a new one from www.sethcoder.com
// You should edit the variables in this file to 
// customize your website.
// 
//////////////////////////////////////////////////////////////////////////////

$RFS_SITE_SESSION_ID        = "RFS_CMS_";
$RFS_SITE_SESSION_USER      = $_SESSION['valid_user'];
$RFS_SITE_ADMIN             = "Administrator";
$RFS_SITE_ADMIN_EMAIL       = "admin@".$SERVER['DOCUMENT_ROOT'];
$RFS_SITE_SLOGAN            = "A RFS CMS Website";
$RFS_SITE_URL               = $SERVER['DOCUMENT_ROOT'];
$RFS_SITE_PATH              = getcwd();

$RFS_SITE_ERROR_LOG         = "/var/log/apache2/error.log";

//////////////////////////////////////////////////////////////////////////////
// WIKI STUFF

$RFS_SITE_WIKI_IMAGES_PATH  = "images/wiki";

//////////////////////////////////////////////////////////////////////////////
// FONT STUFF

$RFS_SITE_NAV_FONT          = "Impact.ttf";
$RFS_SITE_NAV_IMG           = "0";

//////////////////////////////////////////////////////////////////////////////
// THEME STUFF

$RFS_SITE_DEFAULT_THEME     = "tmpl_test";
$RFS_SITE_FORCE_THEME       = true;
$RFS_SITE_FORCED_THEME      = "tmpl_test";
$RFS_SITE_THEME_CSS_URL     = "";
$RFS_SITE_THEME_DROPDOWN    = false;

//////////////////////////////////////////////////////////////////////////////
// Facebook integration

$RFS_SITE_FACEBOOK_APP_ID   = "";
$RFS_SITE_FACEBOOK_SECRET   = "";
$RFS_SITE_FACEBOOK_SDK      = "$RFS_SITE_PATH/facebook/src/facebook.php";


///  wiki stuff

$rfsw_header        = "header.php"; 
$rfsw_footer        = "footer.php";

$rfsw_dbname        = "trainweb";   // change these
$rfsw_address       = "localhost";  // variables
$rfsw_user          = "training";   // to work with
$rfsw_pass          = "!QAZ2wsx";           // your database

$rfsw_img_path      = "images/wiki";       // path to image uploads
$rfsw_bullet_image  = "$rfsw_img_path/bullet.gif";
$rfsw_admin_mode    = "false";


//////////////////////////////////////////////////////////////////////////////

$RFS_SITE_CSS               = "<LINK rel=\"stylesheet\" href=\"\$RFS_SITE_THEME_CSS_URL\" type=\"text/css\">\n";

$RFS_SITE_MENU_TOP_LOCATION = "top";
$RFS_SITE_MENU_LEFT_LOCATION= "left";
$RFS_SITE_FOOTER            = "";
$RFS_SITE_COPYRIGHT         = "<center><a href=\"http://www.sethcoder.com/\"> Powered by RFS CMS &copy;2005-2012 Seth Parson</a></center>";

$RFS_SITE_SINGLETABLEWIDTH  = 940;
$RFS_SITE_DOUBLETABLEWIDTH  = 435;
$RFS_SITE_MENU_TOP_LOCATION = "top";
$RFS_SITE_SHOW_LINK_FRIENDS = true;
$RFS_SITE_SHOW_TOP_REFERRERS= true;
$RFS_SITE_SHOW_ONLINE_USERS = false;
$RFS_SITE_SHOW_LINK_BIN     = true;
$RFS_TAGS=array(
"RFS_SITE_LOGIN_FORM_CODE"  => "<!--RFS_LOGIN_FORM-->",
"RFS_SITE_FUNCTION"         => "<!--RFS_FUNCTION-->"
);
$RFS_SITE_FUNCTION          = "run a function";

$RFS_SITE_LOGIN_FORM_CODE   = "<script src=\"\$RFS_SITE_URL/include/md5.js\"> </script>
<form method=post action=\"\$RFS_SITE_URL/login.php\">
<input type=hidden name=outpage value=\"\$thispage\">
<input type=hidden name=action value=\"logingo\">
<table align=right border=0 cellspacing=0 cellwidth=0 cellpadding=0 valign=middle>\n
<tr valign=middle>\n
<td align=right class=toptd><font class=slogan>Login</font></td>
<td class=toptd><input type=text name=userid size=10 class=\"b4text\"></td><td>
 &nbsp;(<a href=\$RFS_SITE_URL/login.php?action=join&outpage=$PHP_SELF>register</a>)
</td></tr><tr>\n<td align=right class=toptd>
<font class=slogan>Password</font></td>\n<td class=toptd>
<input type=password name=password size=10 class=\"b4text\"></td>\n
<input type=hidden name=outpage value=$PHP_SELF>\n
<input type=hidden name=login value=fo_shnizzle>\n
<td valign=middle>\n <input type=\"submit\" name=\"Login\" value=\"Login\">\n
</td>\n</form>\n<td>\n</td>\n</tr>\n</table>\n
";


$RFS_SITE_JOIN_FORM_CODE        = "
<p>Your information will not be shared with anyone.</p>
<table border=0 cellspacing=0 cellpadding=0>
<form method=post action=\"$RFS_SITE_URL/login.php\">
<input type=hidden name=action value=join_go>
<tr><td> User ID </td><td><input type=textbox  name=userid value=\"$userid\">  </td></tr>
<tr><td> Email   </td><td><input type=textbox  name=email value=\"$email\">    </td></tr>
<tr><td>         </td><td> </td></tr>
<tr><td>         </td><td><input type=\"submit\" name=\"Register\" value=\"Register\"></td></tr>
</form></table>
";

$RFS_SITE_LOGGED_IN_CODE   = "<div class=logged_in_box>\$RFS_SITE_SESSION_USER (<a href=\$RFS_SITE_URL/login.php?action=logout>logout</a>)</div>";

//////////////////////////////////////////////////////////////////////////////
// 3rd Party Files
// java script locations

$RFS_SITE_JS_JQUERY         = "<script src=\"\$RFS_SITE_URL/3rdparty/jquery/jquery.js\"></script>";
$RFS_SITE_JS_JQUERY_UI      = "<script src=\"\$RFS_SITE_URL/themes/\$theme/jqueryui.js\"></script>";
$RFS_SITE_JS_JQUERY_UI_CSS  = "<link type=\"text/css\" href=\"\$RFS_SITE_URL/themes/\$theme/jqueryui.css\" rel=\"stylesheet\" />";
$RFS_SITE_JS_COLORPICKER    = "<script src=\"\$RFS_SITE_URL/3rdparty/colorpicker/js/colorpicker.js\"></script>";
$RFS_SITE_JS_MOOTOOLS       = "<script src=\"\$RFS_SITE_URL/3rdparty/mootools/mootools.js\"></script>";
$RFS_SITE_JS_EDITAREA       = "<script src=\"\$RFS_SITE_URL/3rdparty/editarea/edit_area/edit_area/js\"></script>";

//////////////////////////////////////////////////////////////////////////////
// Figure out what to put in the title...
// If you're not sure what to put, just leave it alone
/*
@include_once("../lib.news.php");
@include_once("lib.news.php");
        $title=$_GLOBALS['site_name'];
        if($_SERVER['PHP_SELF']==$_GLOBALS['site_url'].'/index.php')
            $title=sc_get_news_headline(sc_get_top_news_id());
        if(!empty($description)) $title=$description;
        if(!empty($name)) $title=$name;
        if(!empty($sname)) $title=$sname;
            if(stristr( $_SERVER['PHP_SELF'],"pics.php")) {
                $title="Pictures";
                if(!empty($id)) {
                $p=mfo1("select * from pictures where id='$id'");
                if(!empty($p->sname)){
                    $title=$p->sname;
                }
                else {
                    $c=mfo1("select * from categories where id='$p->category'");
                    if(!empty($c->name)) {
                        $title=$c->name;
                    }
                }
            }
        }

        if(!empty($_GET['nid']))
            $title=sc_get_news_headline($_GET['nid']);
        if(!empty($what)) $title=$what;
        */

$RFS_SITE_TITLE         ="<TITLE> \$RFS_SITE_NAME $title </TITLE>";

//////////////////////////////////////////////////////////////////////////////
// KEYWORDS

$RFS_SITE_SEO_KEYWORDS = $title."";

//////////////////////////////////////////////////////////////////////////////
// Unlikely to need changes

$RFS_SITE_DOC_TYPE          = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
$RFS_SITE_HTML_OPEN         = "<HTML>";
$RFS_SITE_HEAD_OPEN         = "<HEAD>
                               <META NAME=\"ROBOTS\" CONTENT=\"INDEX,FOLLOW\">
                               <META http-equiv=\"Content-Language\" content=\"en-us\">
                               <META http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
                               <META name=\"GENERATOR\" content=\"RFS CMS\">
                               <META name=\"ProgId\" content=\"RFS CMS\">
                               <META name=\"description\" content=\"$RFS_SITE_SEO_KEYWORDS \">
                               <META name=\"keywords\" content=\"$RFS_SITE_SEO_KEYWORDS \">\n";
$RFS_SITE_HEAD_CLOSE        = "</HEAD>\n";
$RFS_SITE_BODY_OPEN         = "<BODY topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n\n";

$RFS_SITE_BODY_CLOSE        = "</BODY>\n";
$RFS_SITE_HTML_CLOSE        = "</HTML>\n";

$RFS_SITE_DELIMITER         = "¥";


/////////////////////////////////////////////////////////////////////////////////////////
// This file can not have any trailing spaces
?>
