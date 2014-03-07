<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// LOAD VARIABLE DEFAULTS
foreach($_REQUEST as $k => $v) $GLOBALS["$k"]=$v;
include_once("lib.mysql.php");
include_once("config/config.sitevars.php");
// Fill in site variables from database
$res=lib_mysql_query("select * from `site_vars`");
for($i=0;$i<@mysql_num_rows($res);$i++) {
    $site_var=mysql_fetch_object($res);
    $upsitevar=strtoupper($site_var->name);
    $GLOBALS["RFS_SITE_$upsitevar"]=stripslashes($site_var->value);
}

foreach($GLOBALS as $key => $value){
    //$value=tostring($value);
    if(is_string($value)){
       //if(stristr($key,"RFS_"))
       //echo "[$key] => [".str_replace("<","&lt;",$value)."]<br>";
    }
}

if(!isset($RFS_SITE_SUDO_CMD)) $RFS_SITE_SUDO_CMD=" ";

/////////////////////////////////////////////////////////////////////////////////////////
// This file can not have any trailing spaces
?>