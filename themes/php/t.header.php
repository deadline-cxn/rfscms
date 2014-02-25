
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 
 <? 
 rfs_echo($RFS_SITE_TITLE);
 ?>
 
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
	if($_SESSION['logged_in']) {
		rfs_echo($RFS_SITE_LOGGED_IN_CODE);
	}
	else {
		rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
	}
 ?>
</div>

