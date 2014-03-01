<?
rfs_echo($RFS_SITE_DOC_TYPE);
rfs_echo($RFS_SITE_HTML_OPEN);
rfs_echo($RFS_SITE_HEAD_OPEN);
rfs_echo($RFS_SITE_TITLE);

$RFS_SITE_THEME_CSS_URL="$RFS_SITE_URL/themes/$theme/t.css";

rfs_echo($RFS_THEME_CSS);

rfs_echo($RFS_SITE_HEAD_CLOSE);
rfs_echo($RFS_SITE_BODY_OPEN);
echo "<center>";

to("100%"," align=center cellpadding=0");

    tro("");
        tco("verytoptd");
    
            to("100%"," class=toptd");
                tro("");
                    tco("toptd");

                    echo "$RFS_SITE_NAME";
                    echo "<font class=slogan><BR>$RFS_SITE_SLOGAN</font>";
                    tcr("toptd");
					/*
                    echo '
                    <!-- Facebook Badge START -->
						<a href="http://en-gb.facebook.com/seth.parson" 
						target="_TOP" title="Seth Parson">
						<img src="http://badge.facebook.com/badge/1321508503.3376.1325567341.png" 
						style="border: 0px;" height=70%/></a><!-- Facebook Badge END -->
								  
									<!-- Facebook Badge START -->                    
						<a href="http://www.facebook.com/DefectiveMinds" target="_TOP" title="Defective Minds">
						<img src="http://badge.facebook.com/badge/252282598232241.911.982775265.png" style="border: 0px;" height=70% />
						</a><!-- Facebook Badge END -->
									';
					*/
					 tcr("toptd");
					 
					 if(!sc_yes($data->donated)) {
					 		sc_donate_button();
							sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
							//sc_reddit();
							//sc_social_buttons();
					 
					 }
                    tcc();
                trc();
            tc();
        tcc();
    trc();
tc();

echo "<div style=\"height:3px;\"></div>";
echo "<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td style=\"border: 1px solid #000000;\">";

to("100% cellpadding=3"," align=center ");
    tro("");
        sc_menu_draw($RFS_THEME_MENU_TOP_LOCATION);
    trc();
tc();
echo "</td></tr></table>";

echo "<div style=\"height:3px;\"></div>";

echo "<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td style=\"border: 1px solid #000000;\">";

to("100% cellpadding=5"," align=center ");
    tro("");
        tco("thirdtd");

			tcr("thirdtd ");

			sc_theme_form();

			tcr("thirdtd ");
			
			if(empty($data->donated))

			tcr("thirdtd align=center");

				
				
           tcr("thirdtd align=right ");

           if($_SESSION['logged_in']) {
				rfs_echo($RFS_SITE_LOGGED_IN_CODE);				
		   }
           else {
			    rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
		   }
            
        tcc();
    trc();
tc();
echo "</td></tr></table>";

echo "<div style=\"height:3px;\"></div>";

echo "<table width=100% cellpadding=0 cellspacing=0 border=0><tr>";
echo "<td class=\"lefttd\" valign=top>";
sc_draw_module("left");
echo "</td>";
tco("midtd");

echo "<script>


</script>";



?>