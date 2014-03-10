<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
lib_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_phpself() { eval(lib_rfs_get_globals()); 
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
	$link="http://".$a[2]."/";
	return $link;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_domain($domain) {
    lib_mysql_query("insert into `banned` (`domain`) VALUES ('$domain')");
    //$res=lib_mysql_query("select * from `link_bin` where `link`='$domain'");
    //if(mysql_num_rows($res))
    lib_mysql_query("update `link_bin` set `banned`='yes' where `link`='$domain'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_ref($refer){
    $res=mysql_num_rows(lib_mysql_query("select * from banned where `link`='$refer'"));
    if($res==0) lib_mysql_query("insert into `banned` (`link`) VALUES ('$refer')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_ban_ip($ip){
    $res=mysql_num_rows(lib_mysql_query("select * from banned where `ip`='$ip'"));
    if($res==0) lib_mysql_query("insert into `banned` (`ip`) VALUES ('$ip')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_unban_domain($domain){
	lib_mysql_query("delete from `banned` where `domain`='$domain'");
    // $res=mysql_num_rows(lib_mysql_query("select * from banned where `domain`='$domain'"));
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
    if(mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_banned_ref($refer){
    $res=lib_mysql_query("select * from `banned` where `link`='$refer'");
    if(@mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_domain_banned_ip($ip){
    $res=lib_mysql_query("select * from `banned` where `ip`='$ip'");
    if(mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
?>