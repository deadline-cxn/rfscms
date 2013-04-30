<?
	echo "<BR><BR><BR>";
	if(empty($data->donated)) {
		sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	}
	else {
		
	}
	echo "</td>";
	echo "<td class=\"righttd\" style=\"vertical-align:text-top;\" >";
	sc_draw_module("right");
	echo "</td></tr></table>";

	echo "<BR><BR><BR>";

    echo "<center>$RFS_SITE_COPYRIGHT</center>";
	for($i=0;$i<10;$i++) echo "<p> &nbsp; </p> <br> &nbsp; <br>";
    echo "</body></html>";

?>