<?

include("$RFS_SITE_URL/include/lib.sitevars.php");


tcc();
trc();
tc();

d_echo("[tmpl_test.footer.php]");

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
echo "<p>";
rfs_echo($RFS_SITE_COPYRIGHT);
echo "</p>";

rfs_echo($RFS_SITE_BODY_CLOSE);
rfs_echo($RFS_SITE_HTML_CLOSE);


?>


