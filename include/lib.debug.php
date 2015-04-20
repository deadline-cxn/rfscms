<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
include_once("lib.access.php");
include_once("lib.modules.php");
/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_REQUEST['debug'])) {
	if($_REQUEST['debug']=="on")  lib_debug_on();
	if($_REQUEST['debug']=="off") lib_debug_off();
}
function lib_debug_on()  {
	if(lib_access_check("debug","view"))
		$_SESSION['debug_msgs']=true;
}
function lib_debug_off() {
    if(!empty($_SESSION['debug_msgs']))
	$_SESSION['debug_msgs']=false;
}
if(!empty($_REQUEST['clear_error_log'])) if($_REQUEST['clear_error_log']=="true") { $dout.=system("rm $RFS_SITE_ERROR_LOG"); }
if(!empty($_REQUEST['debug_view_error_log'])) if($_REQUEST['debug_view_error_log']==1) {
	echo "<pre style='background-color: #000000; color: #00FF00;'> ERROR LOG: ";
	$cmd="sudo cat $RFS_SITE_ERROR_LOG";
	echo " $cmd ";
	echo system( $cmd );
	echo "</pre>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function d_backtrace() {

	echo "<div align='left'style='color:red; background-color:black; width: 100%;'>DEBUG:";
        var_dump(debug_backtrace());
        echo "</div>";

}
function d_echo($t){
	if(!lib_access_check("debug","view")) return;
    if(isset($_SESSION['debug_msgs']))
    if(lib_rfs_bool_true($_SESSION['debug_msgs'])){
        $t=str_replace("<","&lt;",$t);
        $tx=explode($GLOBALS['RFS_SITE_DELIMITER'],$t);
        for($ti=0;$ti<count($tx);$ti++){
            echo "<div align='left'style='color:red; background-color:black; width: 100%;'>DEBUG:";
            echo str_replace("\n'","'",$tx[$ti]);
            echo "</div>";
        }
    }
}
if(!function_exists("lib_div")) {
function lib_div($t) {	
	if(!isset($GLOBALS['RFS_GEN_IMAGE'])) {	
		d_echo("<!-- ******************** ($t) ******************** -->\n");
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_debug_tail_error_log() { global $RFS_SITE_ERROR_LOG;	
	if(empty($RFS_SITE_ERROR_LOG)) {
        $RFS_SITE_ERROR_LOG="/var/log/apache2/error.log";
        if(@file_exists("error_log")) { $RFS_SITE_ERROR_LOG="error_log"; }		
        if(@file_exists("/var/www/errors.log")) { $RFS_SITE_ERROR_LOG="/var/www/errors.log"; }
        if(@file_exists("/var/www/error.log")) { $RFS_SITE_ERROR_LOG="/var/www/error.log"; }
	}	
    echo "<pre style='background-color: #000000; color: #00FF00;'> ERROR LOG: ";
	$cmd="sudo tail $RFS_SITE_ERROR_LOG";
	echo " $cmd ";
	echo system( $cmd );    
    echo "</pre>";
	echo "<p>";
	echo "[<a href=./?debug_view_error_log=1>VIEW FULL ERROR LOG</a>]";
    echo "[<a href=./?clear_error_log=true>CLEAR ERROR LOG</a>]";
	echo "</p>";

}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_debug_header($quiet) { eval(lib_rfs_get_globals()); 
	lib_div("lib_debug_debugheader start");
	//$dout ="<p align=left><pre>";
	$dout ="\$data->theme=$data->theme".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "\$RFS_SITE_SESSION_ID: ".$RFS_SITE_SESSION_ID.$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "VALID USER: ".$_SESSION["valid_user"].$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "LOGGED IN: ".$_SESSION["logged_in"].$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "THEME: $RFS_SITE_PATH/themes/$theme".$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "USER ID: ".$data->id.$GLOBALS['RFS_SITE_DELIMITER'];
	$dout.= "DATABASE UPGRADE: ".$GLOBALS['RFS_SITE_DATABASE_UPGRADE'];
	//$dout.="</pre></p>";
	if(!$quiet) d_echo($dout);
    if(!empty($_SESSION['debug_msgs']))	
	if($_SESSION['debug_msgs']=="true") { 	lib_debug_tail_error_log(); }
	return $dout;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_debug_footer($quiet) { eval(lib_rfs_get_globals());
	//$dout ="<p align=left><pre>";
	$dout="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
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
	
    foreach( $_SESSION as $k=>$v ) {
        if(is_array($v)) $v="(Array)";
		$dout.="\$_SESSION['$k']='$v'".$GLOBALS['RFS_SITE_DELIMITER'];
     }
        
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
	$res=lib_mysql_query("select * from `site_vars`");
	
    while($sv=$res->fetch_object()) { 
        
        
                
		$dout.="\$RFS_SITE_". ($sv->name)."='$sv->value'<br>".$GLOBALS['RFS_SITE_DELIMITER'];
        
    }	
	$dout.="======================================================================".$GLOBALS['RFS_SITE_DELIMITER'];
    if(empty($theme)) $theme="";
	$dout.="[footer.php \$theme=$theme]";
	//$dout.="</pre></p>";
	if(!$quiet) d_echo($dout);
	return $dout;
}
