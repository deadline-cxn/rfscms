<?
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    echo "<html><head>\n";
    
    echo "<META NAME=\"ROBOTS\" CONTENT=\"INDEX,FOLLOW\">\n";
    echo "<meta http-equiv=\"Content-Language\" content=\"en-us\">\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">\n";
    echo "<meta name=\"GENERATOR\" content=\"Notepad\">\n";
    echo "<meta name=\"ProgId\" content=\"Notepad\">\n";
    
    $keywords=$_GET['query'];
    if(empty($keywords))
    $keywords=$_GET['q'];
    if(empty($keywords))
    $keywords="php c c++ code content management system cms rfs download software tools development tutorial wiki example video picture file image project visual studio code block web www w3c html javascript";
    
    echo "<meta name=\"description\" content=\"$keywords\">\n";
    echo "<meta name=\"keywords\" content=\"$keywords\">\n";
    
    echo $RFS_SITE_TITLE;
	echo "<link rel=\"canonical\" href=\"".sc_phpself()."\" />\n";
    echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
    echo "</head>\n";
    
    echo "<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0>\n";
    
    echo "<table border=0 width=100% height=50 cellspacing=0 cellpadding=0 class=toptd>";
    echo "<tr><td class=toptd align=left width=80%>";
    
    
    if(file_exists("$RFS_SITE_PATH/themes/$theme/t.top_image.gif"))
    echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_image.gif\" align=left>";

    echo "<font class=toptd>$keywords</font> ";
    
    echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
    
    echo "<td class=toptd valign=bottom>";
    
    if(file_exists("$RFS_SITE_PATH/themes/$theme/t.bot_right_corner.gif"))    {
        echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.bot_right_corner.gif\" align=right valign=bottom>";
        echo "</td><td class=contenttd>";
    }
    else
        echo "&nbsp;";
    
    
    
    if(!sc_yes($_SESSION["logged_in"])){
        rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
        
    }
    else
    {
        
        echo "</td><td class=contenttd>&nbsp;</td><td class=contenttd>";    
        echo $_SESSION["valid_user"];
        echo " (<a href=\$RFS_SITE_URL/login.php?action=logout>logout</a>)<BR>";
		
    }
    
    
    echo "</td><td class=contenttd>&nbsp;</td></tr></table>";
    
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
    echo "<td width=160 class=lefttd valign=top>";
    
    echo "<table border=0 cellpadding=5 cellspacing=0 width=100%><tr><td valign=top class=lefttd>";
    
    if(file_exists("$RFS_SITE_PATH/leftbar.php"))
        include("$RFS_SITE_PATH/leftbar.php");
    
    echo "</td></tr></table>";
    
    echo "</td><td valign=top class=midtd>";
    
    
    echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
    
    
    echo "<tr><td valign=top class=midtd width=41>";
    
    
    if(file_exists("$RFS_SITE_PATH/themes/$theme/t.top_left_corner.gif"))
    {
        echo "<img src=\"$RFS_SITE_URL/themes/$theme/t.top_left_corner.gif\">";
        echo "</td><td>";
    }
    else
        echo "&nbsp;";
    
    
    echo "</td> <td class=midtd> ";
    
    
    echo " &nbsp; </td></tr>";
    
    
    echo "<tr><td width=41></td><td>";
?>
