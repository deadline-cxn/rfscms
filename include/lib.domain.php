<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_last_url_element($url) {
	$t=explode("/",$url);
	$u=explode("?",$t[sizeof($t)-1]);
	return $u[0];
}
function lib_domain_get_current_pagename() {
    $x=lib_domain_phpself();
    $y=explode("/",$x);
    $x=$y[count($y)-1];
    return $x;
}
function lib_domain_phpself() {
    eval(lib_rfs_get_globals()); 
	$page=$_SERVER['PHP_SELF'];
	return $page;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_canonical_url(){
	$page_url = 'http';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
		$page_url .= 's';
	}
	return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_gotopage($x) {
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$x\">";
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_getdomain($link) {
	$a=explode("/",$link,4);
	$link=$a[2];//"http://".$a[2]."/";
	return $link;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_domain($domain) {
    lib_mysql_query("insert into `banned` (`domain`) VALUES ('$domain')");
    //$res=lib_mysql_query("select * from `link_bin` where `link`='$domain'");
    //if($res->num_rows)
    lib_mysql_query("update `link_bin` set `banned`='yes' where `link`='$domain'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_ref($refer){
	$res=lib_mysql_query("select * from banned where `link`='$refer'");
    $res=$res->num_rows;
    if($res==0) lib_mysql_query("insert into `banned` (`link`) VALUES ('$refer')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_ip($ip){
    
	$res=lib_mysql_query("select * from banned where `ip`='$ip'");
    if($res->num_rows==0)
		lib_mysql_query("insert into `banned` (`ip`) VALUES ('$ip')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_unban_domain($domain){
	lib_mysql_query("delete from `banned` where `domain`='$domain'");
    // $res=$res->num_rows(lib_mysql_query("select * from banned where `domain`='$domain'"));
    // if($res==0) 
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_unban_ref($refer){
    lib_mysql_query("delete from `banned` where `link`='$refer'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_unban_ip($ip){
    lib_mysql_query("delete from `banned` where `ip`='$ip'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_banned_domain($domain){
    $res=lib_mysql_query("select * from `banned` where `domain`='$domain'");
    if($res->num_rows) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_banned_ref($refer){
    $res=lib_mysql_query("select * from `banned` where `link`='$refer'");
    if($res->num_rows) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_banned_ip($ip){
    $res=lib_mysql_query("select * from `banned` where `ip`='$ip'");
    if($res->num_rows) return true;
    return false;
}
