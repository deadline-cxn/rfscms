<?
rfs_echo($RFS_SITE_DOC_TYPE);
rfs_echo($RFS_SITE_HTML_OPEN);
rfs_echo($RFS_SITE_HEAD_OPEN);
rfs_echo($RFS_SITE_TITLE);
$RFS_SITE_THEME_CSS_URL="$RFS_SITE_URL/themes/$theme/t.css";
rfs_echo($RFS_SITE_CSS);
rfs_echo($RFS_SITE_HEAD_CLOSE);
rfs_echo($RFS_SITE_BODY_OPEN);
echo "<center>";

to($RFS_SITE_SINGLETABLEWIDTH," align=center ");
    tro("");
        tco("middle_cont");
            to("100%"," class=toptd");
                tro("");
                    tco("toptd");
                    echo "$RFS_SITE_NAME";
                    echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
                    tcc();
                trc();
            tc();
        tcc();
    trc();
tc();


to($RFS_SITE_SINGLETABLEWIDTH+75," align=center ");
    tro("");
        sc_menu_draw($RFS_SITE_MENU_TOP_LOCATION);
    trc();
tc();

to("100%"," align=center ");
    tro("");
        tco("thirdtd valign=bottom");
            
            sc_theme_form();
            
            tcr("thirdtd width=100% align=right ");
            
                if($RFS_SITE_SESSION_USER) {
                    rfs_echo($RFS_SITE_LOGGED_IN_CODE);					
				}
                else {
                    rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
					sc_facebook_login();
				}
            
        tcc();
		tcc();
    trc();
tc();



to($RFS_SITE_SINGLETABLEWIDTH," align=center ");
tro("");
tco("middle_cont");











?>