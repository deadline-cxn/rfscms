<?
function mailgo($email,$message,$subject) {
	eval(lib_rfs_get_globals());
	$email=str_replace("'at'","@",$email);
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$message  = $begin.$message;
	$message .= "<p>Automated message from <a href=$RFS_SITE_URL>$RFS_SITE_NAME</a> ~ Do not reply!</p>\n";
	return mail($email,$subject,$message,"From: $RFS_SITE_ADMIN_EMAIL\r\n$headers");
}
function mailto($user,$domain) { echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=mailto.php?user=$user&domain=$domain\">"; }
function sc_togglediv_ne($x) {
	$id=lib_string_generate_password();	
	$r=	sc_togglediv_start_ne("did_".md5($id),"");
	$r.=$x;
	$r.=sc_togglediv_end_ne();
	return $r;
}
function sc_togglediv($x) {
	echo sc_togglediv_ne($x);
}
function sc_togglediv_start_ne($x,$y,$folded) {
	$fold="[-]"; if($folded) $fold="[+]";
	$foldstate="block"; if($folded) $foldstate="none";
	$anchor=md5($x.$y.$foldstate);
	$titley=str_replace("\"","'",$y);
	$r="<script> state['$x']='$foldstate'; </script>	<a href=\"#\" onclick=\"showhide('$x');\" title=\"$titley\"><div id=\"$x"."plusminus\" style='float:left;'>$fold</div></a>$y<div id=\"$x\" style=\"clear:both; display:$foldstate;\">";
	return $r;
}
function sc_togglediv_start($x,$y,$folded) {
	echo sc_togglediv_start_ne($x,$y,$folded);
}
function sc_togglediv_end_ne() {
	return  "</div>";
}
function sc_togglediv_end() {
	echo sc_togglediv_end_ne();
}

function sc_javascript() { eval(lib_rfs_get_globals());
echo "<script language=\"javascript\">
<!--
var state = {};
function showhide(layer_ref) {
if (state[layer_ref] == 'block') {
	state[layer_ref] = 'none';
	document.getElementById(layer_ref+\"plusminus\").innerHTML=\"[+]\";
} 
else {
	state[layer_ref] = 'block';
	document.getElementById(layer_ref+\"plusminus\").innerHTML=\"[-]\";
} 
if (document.all) { //IS IE 4 or 5 (or 6 beta) 
	eval( \"document.all.\" + layer_ref + \".style.display = state[layer_ref]\"); 
} 
if (document.layers) { //IS NETSCAPE 4 or below 
	document.layers[layer_ref].display = state[layer_ref]; 
} 
if (document.getElementById &&!document.all) { 
	hza = document.getElementById(layer_ref); 
	hza.style.display = state[layer_ref]; 
	} 
} 
//-->
</script>";
}



?>