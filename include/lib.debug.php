<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['debug']=="on") sc_debug_on();
if($_REQUEST['debug']=="off") sc_debug_off();
function sc_debug_on()  { $_SESSION['debug_msgs']=true;  }
function sc_debug_off() { $_SESSION['debug_msgs']=false; }
/////////////////////////////////////////////////////////////////////////////////////////
function d_echo($t){
    if(isset($_SESSION['debug_msgs']))
    if(sc_yes($_SESSION['debug_msgs'])){
        $t=str_replace("<","&lt;",$t);    
        $tx=explode($GLOBALS['RFS_SITE_DELIMITER'],$t);
        for($ti=0;$ti<count($tx);$ti++){
            echo "<div align='left'style='color:red; background-color:black; width: 100%;'>DEBUG:";
            echo str_replace("\n'","'",$tx[$ti]);
            echo "</div>";
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_tail_error_log() { global $RFS_SITE_ERROR_LOG;	
	if(empty($RFS_SITE_ERROR_LOG)) {
        $RFS_SITE_ERROR_LOG="/var/log/apache2/error.log";
        if(@file_exists("error_log")) { $RFS_SITE_ERROR_LOG="error_log"; }		
        if(@file_exists("/var/www/errors.log")) { $RFS_SITE_ERROR_LOG="/var/www/errors.log"; }
        if(@file_exists("/var/www/error.log")) { $RFS_SITE_ERROR_LOG="/var/www/error.log"; }
	}	
    echo "<pre style='background-color: #000000; color: #00FF00;'> ERROR LOG: ";
	$cmd="tail $RFS_SITE_ERROR_LOG";
	echo " $cmd ";
	system( $cmd );    
    echo "</pre>";
    echo "<p><a href=./?clear_error_log=true>CLEAR ERROR LOG</a></p>";

}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_debugheader($quiet) { eval(scg()); 
	sc_div("sc_debugheader start");
	$dout ="\$data->theme=$data->theme".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "\$RFS_SITE_SESSION_ID: ".$RFS_SITE_SESSION_ID.$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "VALID USER: ".$_SESSION["valid_user"].$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "LOGGED IN: ".$_SESSION["logged_in"].$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "THEME: $RFS_SITE_PATH/themes/$theme".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "USER ID: ".$data->id.$GLOBALS['RFS_SITE_DELIMITER'];
	if(!$quiet) d_echo($dout);	
	if($_REQUEST['clear_error_log']=="true") { $dout.=system("rm $RFS_SITE_ERROR_LOG"); } // $fp=fopen("error.log","wt"); fwrite($fp,"error.log\n\r"); fclose($fp); }
	if($_SESSION['debug_msgs']=="true") { 	sc_tail_error_log(); }
	return $dout;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_debugfooter($quiet) {
	$dout ="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="_REQUEST VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_REQUEST as $k=>$v ) $dout.="\$_REQUEST['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout ="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="POST VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_POST as $k=>$v ) $dout.="\$_POST['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="GET VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_GET as $k=>$v )  $dout.="\$_GET['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="GLOBAL VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $GLOBALS as $k=>$v ) {
	if(is_string($v)) $dout.="\$GLOBALS['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER']; }
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="SESSION VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_SESSION as $k=>$v )
		$dout.="\$_SESSION['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="COOKIE VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_COOKIE as $k=>$v )
		$dout.="\$_COOKIE['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( session_get_cookie_params() as $k=>$v )  $dout.=" params['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="_ENV VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_ENV as $k=>$v)     $dout.= "\$_ENV['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="_SERVER VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	foreach( $_SERVER as $k=>$v ) $dout.= "\$_SERVER['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="RFS_SITE VARS:".$GLOBALS['RFS_SITE_DELIMITER'];
	$res=sc_query("select * from `site_vars`");
	for($i=0;$i<mysql_num_rows($res);$i++){
		$sv=mysql_fetch_object($res);
		$dout.="\$RFS_SITE_". ($sv->name)."='$sv->value'<br>".$GLOBALS['RFS_SITE_DELIMITER'];
	}
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.="[footer.php \$theme=$theme]";
	if(!$quiet)
	d_echo($dout);
	return $dout;
}
/////////////////////////////////////////////////////////////////////////////////////////
// This file can not have any trailing spaces
?>