<?
	
	if(empty($data->donated)) {
		sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
	}
	else {
		
	}
	echo "</td>";
	
	echo "<td class=\"righttd\" >";
		sc_draw_module("right");
	echo "</td></tr>";
	echo "</table>";

	

    echo "<center>$RFS_SITE_COPYRIGHT</center>";
	
    echo "</body></html>";

?>