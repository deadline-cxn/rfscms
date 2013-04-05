<?
// LOAD VARIABLE DEFAULTS

$RFS_SITE_NAME          ="RFS Content Management System";
$RFS_SITE_SLOGAN        ="Home of the RFS Content Management System";

$RFS_SITE_SEO_KEYWORDS  ="php c c++ code content management system cms rfs download software tools development tutorial wiki example video picture file image project visual studio code block web www w3c html javascript";
$RFS_SITE_DOC_TYPE      ="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
$RFS_SITE_HTML_OPEN     ="<HTML>\n";

$RFS_SITE_SESSION_USER  =$_SESSION['valid_user'];

$RFS_SITE_HEAD_OPEN = 
"<HEAD>\n
<META NAME=\"ROBOTS\" CONTENT=\"INDEX,FOLLOW\">\n
<META http-equiv=\"Content-Language\" content=\"en-us\">\n
<META http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n
<META name=\"GENERATOR\" content=\"RFS CMS\">\n
<META name=\"ProgId\" content=\"RFS CMS\">\n
<META name=\"description\" content=\"$RFS_SITE_SEO_KEYWORDS\">\n
<META name=\"keywords\" content=\"$RFS_SITE_SEO_KEYWORDS\">\n";

$RFS_SITE_TITLE         ="<TITLE>\$RFS_SITE_NAME</TITLE>\n"; //".titleWithCat()."

$RFS_SITE_THEME_CSS_URL ="";
$RFS_SITE_CSS           ="<LINK rel=\"stylesheet\" href=\"\$RFS_SITE_THEME_CSS_URL\" type=\"text/css\">\n";

$RFS_SITE_HEAD_CLOSE    ="</HEAD>\n";

$RFS_SITE_BODY_OPEN     ="<BODY topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n\n";

$RFS_SITE_MENU_TOP_LOCATION="top";
$RFS_SITE_MENU_LEFT_LOCATION="left";

$RFS_SITE_FOOTER        = "";
    
$RFS_SITE_COPYRIGHT     ="<a href=\"http://www.sethcoder.com/\"> Powered by RFS CMS &copy;2010 Seth Parson</a>";
$RFS_SITE_BODY_CLOSE    ="</BODY>";
$RFS_SITE_HTML_CLOSE    ="</HTML>";


$RFS_SITE_SINGLETABLEWIDTH=940;
$RFS_SITE_DOUBLETABLEWIDTH=435;
$RFS_SITE_THEME_DROPDOWN=true;

$RFS_SITE_MENU_TOP_LOCATION="top";

$RFS_SITE_SHOW_LINK_FRIENDS =true;
$RFS_SITE_SHOW_TOP_REFERRERS=true;
$RFS_SITE_SHOW_ONLINE_USERS =false;
$RFS_SITE_SHOW_LINK_BIN     =true;

$RFS_TAGS=array(
"RFS_SITE_LOGIN_FORM_CODE"  => "<!--RFS_LOGIN_FORM-->",
"RFS_SITE_FUNCTION"         => "<!--RFS_FUNCTION-->"
);

$RFS_SITE_FUNCTION          = "run a function";

$gRFS_SITE_LOGIN_FORM_CODE   =
"
<ftable border=0 cellspacing=0 cellwidth=0 cellpadding=0>\n
<ftr>\n
    <ftd align=right class=contenttd>
        <form method=post action=\"\$RFS_SITE_URL/login.php\">
        <input type=hidden name=outpage value=\"\$thispage\">
        <input type=hidden name=action value=\"logingo\">
        Login
    </ftd>
    <ftd class=contenttd>
        <input type=text name=userid size=10 class=\"b4text\">
    </ftd>
    <ftd>
        &nbsp;(<a href=\$RFS_SITE_URL/join.php>register</a>)
    </ftd>
</ftr>

<ftr>
    <ftd align=right class=contenttd>
        &nbsp;Password
    </ftd>
    <ftd class=contenttd>
        <input type=password name=password size=10 class=\"b4text\">
    </ftd>
        <input type=hidden name=outpage value=index.php>
        <input type=hidden name=login value=fo_shnizzle>\n
    <ftd valign=middle>
        <input type=\"submit\" name=\"Login\" value=\"Login\">\n
        </form>
    </ftd>
    
</ftr>
</ftable>\n";


$RFS_SITE_LOGGED_IN_CODE    = "$RFS_SITE_SESSION_USER (<a href=\$RFS_SITE_URL/logout.php>logout</a>)";


?>
