<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// LOAD VARIABLE DEFAULTS
foreach($_REQUEST as $k => $v) $GLOBALS["$k"]=$v;
include_once("lib.mysql.php");
include_once("config/config.sitevars.php");

$res=lib_mysql_query("select * from `site_vars`");
if($res) {
	while($site_var=$res->fetch_object()) {
    $upsitevar=strtoupper($site_var->name);
    $GLOBALS["RFS_SITE_$upsitevar"]=stripslashes($site_var->value);
	}
}

function lib_sitevars_assign($name,$value,$type,$desc) {
	$name=strtolower($name);
	$name=str_replace("rfs_site_","",$name);
	$name=str_replace("$","",$name);
	$r=lib_mysql_fetch_one_object("select * from `site_vars` where `name`='$name'");
	if(!empty($r->name)) {
		lib_mysql_query("update `site_vars` set `value`='$value' where `name`='$name';");
	} else {
		lib_mysql_query("insert into `site_vars` (`name`,`value`) values ('$name','$value');");
	}
	if(!empty($type)) {
		$type=addslashes($type);
		lib_mysql_query("update `site_vars` set `type`='$type' where `name`='$name';");
	}
	if(!empty($desc)) {
		$desc=addslashes($desc);
		lib_mysql_query("update `site_vars` set `desc`='$desc' where `name`='$name';");
	}
	$GLOBALS['RFS_SITE_$name']=$value;
}
// foreach($GLOBALS as $key => $value) {//$value=tostring($value); if(is_string($value)){//if(stristr($key,"RFS_")) // echo "[$key] => [".str_replace("<","&lt;",$value)."]<br>";}}
if(!isset($RFS_SITE_SUDO_CMD)) $RFS_SITE_SUDO_CMD=" ";

