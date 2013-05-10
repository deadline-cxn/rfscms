
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <title>PHP: PHP 5.4.0 Release Announcement</title>
 <style type="text/css" media="all">
 
  <? echo "@import url(\"$RFS_SITE_URL/themes/$theme/site.css\");\n"; ?>
  <? echo "@import url(\"$RFS_SITE_URL/themes/$theme/phpnet.css\");\n"; ?>
    
 </style>
 <!--[if IE]><![if gte IE 6]><![endif]-->
  <style type="text/css" media="print">
   
   <? echo "@import url(\"$RFS_SITE_URL/themes/$theme/print.css\");\n"; ?>
   
 </style>
<!--[if IE]><![endif]><![endif]-->
 
<link rel="shortcut icon" href="favicon.ico">
<? 
// <script type="text/javascript" src="./PHP  PHP 5.4.0 Release Announcement_files/userprefs.js"></script>
?>
<style type="text/css"></style></head>
<body>


<div id="headnav">
 <? echo "<a href=\"$RFS_SITE_URL\" rel=\"home\">"; 
 echo "<font style=\" font-size: 32px; \">$RFS_SITE_NAME</font>";
 //<img src="./PHP  PHP 5.4.0 Release Announcement_files/php.gif" alt="PHP" width="120" height="67" id="phplogo"> 
 ?>
 </a>
 
 <?
 echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
 ?>
 
 <div id="headmenu">
 <?
$res=sc_query("select * from `menu_top` order by `sort_order` asc");
for($i=0;$i<mysql_num_rows($res);$i++) {
	$link=mysql_fetch_object($res);
	$link->link=urldecode($link->link);
    $showlink=0;
    if($data->access >= $link->access) $showlink=1;
    if($link->access == 0) $showlink=1;
	if($showlink) {
		rfs_echo("<a href=\"$link->link\" ");		
			rfs_echo($link->link);			
		if(!empty($link->target)) {
			rfs_echo("target=\"$link->target\" ");
		}
		echo ">";
		echo $link->name;
		echo "</a> |	";
		

	}
}
 ?>
   
 </div>
</div>

<div id="headsearch">


 <? 
 sc_theme_form();
 /* <form method="post" action="http://www.php.net/search.php" id="topsearch">
  <p>
   <span title="Keyboard shortcut: Alt+S (Win), Ctrl+S (Apple)">
    <span class="shortkey">s</span>earch for
   </span>
   <input type="text" name="pattern" value="" size="30" accesskey="s">
   <span>in the</span>
   <select name="show">
    <option value="all">all php.net sites</option>
    <option value="local">this mirror only</option>
    <option value="quickref" selected="selected">function list</option>
    <option value="manual">online documentation</option>
    <option value="bugdb">bug database</option>
    <option value="news_archive">Site News Archive</option>
    <option value="changelogs">All Changelogs</option>
    <option value="pear">just pear.php.net</option>
    <option value="pecl">just pecl.php.net</option>
    <option value="talks">just talks.php.net</option>
    <option value="maillist">general mailing list</option>
    <option value="devlist">developer mailing list</option>
    <option value="phpdoc">documentation mailing list</option>
   </select>
   <input type="image" src="./PHP  PHP 5.4.0 Release Announcement_files/small_submit_white.gif" class="submit" alt="search">
  </p>
 </form>
 */
	if($_SESSION['logged_in']) {
		rfs_echo($RFS_SITE_LOGGED_IN_CODE);
	}
	else {
/*
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
<input type=hidden name=outpage value=$PHP_SELF>\n
<input type=hidden name=login value=fo_shnizzle>\n
<td valign=middle>\n <input type=\"submit\" name=\"Login\" value=\"Login\">\n</td>
</tr>

<tr>
<td></td>
<td></td>
<td> &nbsp;(<a href=\$RFS_SITE_URL/login.php?action=join&outpage=$PHP_SELF>Register</a>)
</td>
</tr>
</table>
</form>
";*/

		rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
	}
 ?>
</div>

