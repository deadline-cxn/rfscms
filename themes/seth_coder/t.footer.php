<?
	
	if(empty($data->donated)) {
		echo "<center>";
		sc_google_adsense($RFS_SITE_GOOGLE_ADSENSE);
		echo "</center>";
	}
	else {
		
	}
	echo "</td>";
	
	echo "<td class=\"righttd\" >";
		sc_draw_module("right");
	echo "</td></tr>";
	echo "</table>";

	
	echo "<br>";
	echo "<br>";
	echo "<br>";
    echo "<div style='float: right;'>$RFS_SITE_COPYRIGHT</div>";
	
	echo "<br>";
	echo "<br>";
	
	
    echo "</body></html>";

?>
