<?php
/////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////

srand((double) microtime() * 1000000);  // randomize timer

if(isset($RFS_SITE_LOCALE)) setlocale(LC_MONETARY, $RFS_SITE_LOCALE);

function lib_rfs_get_globals() {
	$out="";
	foreach($GLOBALS as $k => $v) {
		if(!stristr($k,"-")) {
		$nmc=$k[0];
		if(is_numeric($nmc)) $k="__".$k;
		if(!is_numeric($nmc))
			if( ($k != 'GLOBALS') &&
				($k != '_ENV') &&
				($k != 'HTTP_ENV_VARS') &&
				($k != 'DOCUMENT_ROOT') &&
			    ($k != 'GATEWAY_INTERFACE') &&
				($k != 'HTTP_ACCEPT') &&
				($k != 'HTTP_ACCEPT_CHARSET') &&
				($k != 'HTTP_ACCEPT_ENCODING') &&
				($k != 'HTTP_ACCEPT_LANGUAGE') &&
				($k != 'PHPRC') &&
				($k != 'HTTP_CACHE_CONTROL') &&
				($k != 'HTTP_CONNECTION') &&
				($k != 'HTTP_COOKIE') &&
				($k != 'HTTP_HOST') &&
				($k != 'HTTP_REFERER') &&
				($k != 'HTTP_USER_AGENT') &&
				($k != 'PATH') &&
				($k != 'QUERY_STRING') &&
				($k != 'REDIRECT_STATUS') &&
				($k != 'REMOTE_ADDR') &&
				($k != 'REMOTE_PORT') &&
				($k != 'REQUEST_METHOD') &&
				($k != 'REQUEST_URI') &&
				($k != 'SCRIPT_FILENAME') &&
				($k != 'SCRIPT_NAME') &&
				($k != 'SERVER_ADDR') &&
				($k != 'SERVER_ADMIN') &&
				($k != 'SERVER_NAME') &&
				($k != 'SERVER_PORT') &&
				($k != 'SERVER_PROTOCOL') &&
				($k != 'SERVER_SIGNATURE') &&
				($k != 'SERVER_SOFTWARE') &&
				($k != 'UNIQUE_ID') &&
				($k != '__utma') &&
				($k != '__utmz') &&
				($k != '__utmb') &&
				($k != '__utmc') &&
				($k != '__atuvc') &&
				($k != 'PHP_SELF') &&
				($k != 'REQUEST_TIME') &&
				($k != '_POST') &&
				($k != 'HTTP_POST_VARS') &&
				($k != '_GET') &&
				($k != 'HTTP_GET_VARS') &&
				($k != '_COOKIE') &&
				($k != 'HTTP_COOKIE_VARS') &&
				($k != '_SERVER') &&
				($k != 'HTTP_SERVER_VARS') &&
				($k != '_FILES') &&
				($k != 'HTTP_POST_FILES') &&
				($k != '_REQUEST') &&
			   (!function_exists($k)) ) {
				   $k=str_replace("\$","_",$k);
				$out.="\$$k=\$GLOBALS['$k'];\n";
			}
		}
	}
	$out.="\$RFS_ADDON_URL=lib_modules_get_url(\"\");\n";
	return $out;
}

function lib_rfs_var($x) {
	$GLOBALS[$x]=$_REQUEST[$x];
	echo "\$$x=[".$GLOBALS[$x]."]<br>";
}

function lib_rfs_do_action() {
	/////////////////////////////////////////////// Automatic action function
    if(empty($_REQUEST['action'])) $action="";
    else $action=$_REQUEST['action'];
    
	$px=explode("/",$_SERVER['PHP_SELF']);
	$_thisfunk=str_replace(" ","_",str_replace(".php","",$px[count($px)-1])."_action_$action");
	@eval("
	
	if(function_exists(\"$_thisfunk\") == true) @$_thisfunk();
		else if(\$_SESSION[\"debug_msgs\"]==true)
			lib_forms_info(\"DEBUG >> WARNING: MISSING $_thisfunk(); \",\"WHITE\",\"BLUE\");");
}
function lib_rfs_maintenance() { 
	eval(lib_rfs_get_globals());	
	global $theme;
	lib_modules_discover();
	$data=lib_users_get_data($_SESSION['valid_user']);
    if(!empty($mc_gross))
	if($mc_gross>0) $data->donated="yes";    
	if(!empty($_REQUEST['theme'])) {
	   if(!empty($theme))
	   $theme=$_REQUEST['theme'];
    }
	if(empty($theme)) {
	   if(!empty($_SESSION['theme']))
	       $theme = $_SESSION['theme'];
    }
	else $_SESSION['theme']=$theme;
	if(empty($theme))                   $theme=$RFS_SITE_DEFAULT_THEME;
	if(lib_rfs_bool_true($RFS_SITE_FORCE_THEME))   $theme=$RFS_SITE_FORCED_THEME;
	if(!empty($theme)) {
		if($_SESSION['logged_in']) {
			if($theme!=$data->theme) {
				lib_mysql_query("UPDATE `users` SET theme='$theme' where name = '$data->name'");
				$data->theme=$theme;
			} else {
				$theme=$data->theme;
			}
		}
	}
	// lib_mysql_scrub("tags","tag");	
	include("$RFS_SITE_PATH/install/database_upgrades.php");
}

function lib_rfs_flush_buffers(){ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
    ob_start(); 
} 

function lib_rfs_bool_true($x) {
	if(is_bool($x)===true) return $x;
	$x=strtolower($x);
	if( (stristr($x,"true")) ||
		(stristr($x,"yes")) ||
		(stristr($x,"on")) ||
		(stristr($x,"1")) )
			return true;
	return false;
}

function lib_rfs_echo($t) {
	echo lib_rfs_get($t);
}

function lib_rfs_get($t) {
	
	foreach($GLOBALS['RFS_TAGS'] as $key => $value) {
		
		//$x=explode("RFS_FTAG",$t);
		// echo("0".$result[0]."1".$result[1]."2".$result[2]."<br>");
		//$z=explode(" ",$x[1]);
		//if(stristr($value,$z[1])) {
			//$y="$key($z[2]);";
			// echo "FOUND... DO:  [$y]";			
			// echo "... RESULT[".eval($y)."]";
		//}
		
		if(stristr($t,$value)) {
			switch($value) {
				case "<!--RTAG_BUTTON":
					$zx=explode("<!--RTAG_BUTTON",$t);
					$yx=explode("-->",$zx[1]);
					$xx=eplode(",",$yx[0]);
					$t=$zx[0];
					break;
				
				case "RFS_TAG_CANONICAL":
					$t=str_replace("$key",lib_domain_canonical_url(),$t);
					break;
					
				case "RFS_TAG_PHP_SELF": 
					$t=str_replace("$key",lib_domain_phpself(),$t);
					break;
					
				case "RFS_TAG_FUNCTION":
					$t=str_replace("$key","RUNNING:($key)($value)",$t);				
					break;
					
				default: 
					if(function_exists($key)) {
						$t=str_replace($value,call_user_func($key),$t);
					}
					else
						$t=str_replace("$value",$GLOBALS[$key],$t);
					break;
			}
		}
	}
	foreach($GLOBALS as $key => $value) { 
		if(is_string($value)) {
			$t=str_replace("\$$key",$value,$t);
		}
	}
	return $t;
}

