<?php
function mailgo($email,$message,$subject) {
	eval(lib_rfs_get_globals());
	$email=str_replace("'at'","@",$email);
	$headers  = "From: $RFS_SITE_ADMIN_EMAIL\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	$message .= "\r\n\r\n<hr>Automated message from <a href=$RFS_SITE_URL>$RFS_SITE_NAME</a>. No need to reply.\r\n";

//todo: add subscribe / unsubscribe

	return mail($email,$subject,$message,$headers);

/*bool mail ( 	string $to ,
		string $subject ,
		string $message [,
		string $additional_headers [,
		string $additional_parameters ]]
		)
*/
}
function mailto($user,$domain) { echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=mailto.php?user=$user&domain=$domain\">"; }
function rfs_togglediv_ne($x) {
	$id=lib_string_generate_password();	
	$r=	rfs_togglediv_start_ne("did_".md5($id),"");
	$r.=$x;
	$r.=rfs_togglediv_end_ne();
	return $r;
}
function rfs_togglediv($x) {
	echo rfs_togglediv_ne($x);
}
function rfs_togglediv_start_ne($x,$y,$folded) {
	$fold="[-]"; if($folded) $fold="[+]";
	$foldstate="block"; if($folded) $foldstate="none";
	$anchor=md5($x.$y.$foldstate);
	$titley=str_replace("\"","'",$y);
	$r="<script> state['$x']='$foldstate'; </script>	<a href=\"#\" onclick=\"showhide('$x');\" title=\"$titley\"><div id=\"$x"."plusminus\" style='float:left;'>$fold</div></a>$y<div id=\"$x\" style=\"clear:both; display:$foldstate;\">";
	return $r;
}
function rfs_togglediv_start($x,$y,$folded) {
	echo rfs_togglediv_start_ne($x,$y,$folded);
}
function rfs_togglediv_end_ne() {
	return  "</div>";
}
function rfs_togglediv_end() {
	echo rfs_togglediv_end_ne();
}

function rfs_javascript() { eval(lib_rfs_get_globals());
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

