<?
rfs_echo($RFS_SITE_DOC_TYPE);
rfs_echo($RFS_SITE_HTML_OPEN);
rfs_echo($RFS_SITE_HEAD_OPEN);
rfs_echo($RFS_SITE_TITLE);
$RFS_SITE_THEME_CSS_URL="$RFS_SITE_URL/themes/$theme/t.css";
rfs_echo($RFS_SITE_CSS);
rfs_echo($RFS_SITE_HEAD_CLOSE);
rfs_echo($RFS_SITE_BODY_OPEN);
echo "
<div class=\"site_logo\">\n
    <h2>\n
        $RFS_SITE_NAME\n
    </h2>\n
    <ul>\n
        <li>\n
            $RFS_SITE_SLOGAN\n
        </li>\n
    </ul>\n
</div>\n\n";

if(empty($RFS_SITE_MENU_TOP_LOCATION)) $RFS_SITE_MENU_TOP_LOCATION="top";
sc_menu_draw($RFS_SITE_MENU_TOP_LOCATION);

$li="_logged_out";
if($_SESSION['logged_in'])
    $li="_logged_in";
   
echo "<div class=\"loginbar$li\">\n";
    echo "<div class=\"lib_theme\">\n";
		sc_theme_form();        
    echo "</div>\n\n";

    echo "<div class=\"lib_login\">\n";
        if($_SESSION['logged_in'])
            rfs_echo($RFS_SITE_LOGGED_IN_CODE);
        else {
            rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
			sc_facebook_login();
		}
    echo "\n</div>\n\n";

echo "</div>\n";

echo "<div class=page>";


?>