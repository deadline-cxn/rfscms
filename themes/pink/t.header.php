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

to("100%"," align=center cellpadding=0");

    tro("");
        tco("middle_cont");
    
            to("100%"," class=toptd");
                tro("");
                    tco("toptd");

                    echo "$RFS_SITE_NAME";
                    echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
    
    				 tcr("toptd");
							sc_social_buttons();
                    tcc();
                trc();
            tc();
        tcc();
    trc();
tc();

to("100%"," align=center ");
    tro("");
    
        sc_menu_draw($RFS_SITE_MENU_TOP_LOCATION);
        
    trc();
tc();

to("100% cellpadding=0"," align=center ");
    tro("");
        tco("thirdtd");

			tcr("thirdtd ");

			echo "<br>";

			sc_theme_form();

			tcr("thirdtd ");
			
			if(empty($data->donated)) sc_donate_button();
			
			tcr("thirdtd align=center");

				sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	
			echo  " <!-- 123 $RFS_SITE_GOOGLE_ADSENSE 456 -->";

           tcr("thirdtd align=right ");

           if($_SESSION['logged_in']) {
				rfs_echo($RFS_SITE_LOGGED_IN_CODE);				
		   }
           else {
			    rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
				sc_facebook_login();
		   }
        tcc();
    trc();
tc();



to("90%"," align=center ");
tro("");
tco("middle_cont");











?>
