<?		tcc();
	trc();
tc();

			d_echo("[$theme.footer.php]");

			if(empty($data->donated)){
				echo "<table border=0 width=100% ><tr><td align=center>";
				if(empty($data->donated))  {
				  sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
				}
				else {
				 /// -- sc_info("Thanks for donating!","GREEN","BLACK");
				}
				echo "</td></tr></table>";
			}

			echo "<p align=center>";
            
            
			
            sc_image_text($RFS_SITE_COPYRIGHT,"CFRevolution.ttf",
            18,812,44,0,-10,250,0,0,0,0,0,1,1);	
            
           
                
                
			echo "</p>";

	rfs_echo($RFS_SITE_BODY_CLOSE);
rfs_echo($RFS_SITE_HTML_CLOSE);


?>



