<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 
 <? 
 lib_rfs_echo($RFS_SITE_TITLE);
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

<style type="text/css"></style></head>
<body>


<div id="headnav">
 <?
 
 echo "<a href=\"$RFS_SITE_URL\" rel=\"home\">"; 
 
 if ($RFS_THEME_TTF_TOP)  {
			$clr 	= lib_images_html2rgb($RFS_THEME_TTF_TOP_COLOR);
           $bclr	= lib_images_html2rgb($RFS_THEME_TTF_TOP_BGCOLOR);
			echo lib_images_text(
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
		else
 echo "<font style=\" font-size: 32px; \">$RFS_SITE_NAME</font>";
 
 ?>
 </a>
 
 <?
 echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
 ?>
 
 <div id="headmenu">
 <?
$res=lib_mysql_query("select * from `menu_top` order by `sort_order` asc");
for($i=0;$i<$res->num_rows;$i++) {
	$link=$res->fetch_object();
	$link->link=urldecode($link->link);
    $showlink=0;
    if($data->access >= $link->access) $showlink=1;
    if($link->access == 0) $showlink=1;
	if($showlink) {
		lib_rfs_echo("<a href=\"$link->link\" ");		
			lib_rfs_echo($link->link);			
		if(!empty($link->target)) {
			lib_rfs_echo("target=\"$link->target\" ");
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
 lib_forms_theme_select();
	if($_SESSION['logged_in']) {
		lib_rfs_echo($RFS_SITE_LOGGED_IN_CODE);
	}
	else {
		lib_rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
	}
 ?>
</div>

