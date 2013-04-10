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

to("100%","cellpadding=0 cellspacing=0");
    tro("");
        tco("top_top");
        
            if($_SESSION['valid_user']) {
                $t=sc_getusername($_SESSION['valid_user']);
                if($RFS_SITE_NAV_IMG == 1) {
                    if(empty($t)) $t="ERROR!";
                    sc_image_text($t,$RFS_SITE_NAV_FONT,
                                        18,812,44,0,-10,
                                        250,250,40,
                                        44,4,0,1,1);
                
                    rfs_echo("<a href=\$RFS_SITE_URL/logout.php>");

                    sc_image_text("logout",$RFS_SITE_NAV_FONT,
                                    18,812,44,0,0,
                                    250,50,40,
                                    44,4,0,1,1);
                    echo "</a>";
                        
                }
                else {
                    echo $t;
                    rfs_echo("<a href=\$RFS_SITE_URL/logout.php>");
                    echo "logout";
                    echo "</a>";
                }
            }
            else
                rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
            
            echo "</td><td>";

            sc_donate_button();
            
            echo "</td><td width=450>";
            sc_socials_content('','');
            echo "</td><td width=65%>";
                      
          
           
        tcc();
    trc();
tc();

to("100%"," align=center cellpadding=0");

    tro("");
        tco("middle_cont");
    
            to("100%"," class=toptd");
                tro("");
                    tco("toptd");
                    
                    
		if ($RFS_SITE_NAV_IMG)  {
	
			sc_image_text($RFS_SITE_NAME,$RFS_SITE_NAV_FONT,
							53,720,90,0,0,
							250,160,10,
							244,4,0,1,1);
		}else {
			echo "<img src='$RFS_SITE_URL/themes/$theme/t.title.png' border=0>";
		}

					echo "</td><td align=right>";
                    
                    if(empty($data->donated))
                        sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
                    
                    if(!empty($data->donated)) {
								//$RFS_SITE_NAV_FONT,                        
								sc_image_text("DONATED! THANK YOU",

										"Collegiate.ttf",


										53,720,90,0,0,
										255,255,10,
										244,4,0,1,1);
												// 720 x 90
						}
								
				
                
                    
                    tcc();
                trc();
            tc();
        tcc();
    trc();
tc();


//to($RFS_SITE_SINGLETABLEWIDTH+75," align=center ");
to("100%"," align=center ");
    tro("");
    
        sc_menu_draw($RFS_SITE_MENU_TOP_LOCATION);

        theme_form();
        
    trc();
tc();

//to($RFS_SITE_SINGLETABLEWIDTH-75," align=center ");
/*

{
to("100% cellpadding=0"," align=center ");
    tro("");
        tco("thirdtd align=center");
	       // tcr("thirdtd align=center");
    
                          
        tcc();
    trc();
tc();
}
*/



// to($RFS_SITE_SINGLETABLEWIDTH," align=center ");
to("100%"," align=center ");
tro("");
tco("middle_cont");











?>
