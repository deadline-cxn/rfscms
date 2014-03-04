<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
lib_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
function sc_phpself() { eval(scg()); 
	$page=$_SERVER['PHP_SELF'];
	return $page;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_canonical_url(){
	$page_url = 'http';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
		$page_url .= 's';
	}
	return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_gotopage($x) {
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$x\">";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_getdomain($link) {
	$a=explode("/",$link,4);
	$link="http://".$a[2]."/";
	return $link;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_ban_domain($domain) {
    sc_query("insert into `banned` (`domain`) VALUES ('$domain')");
    //$res=sc_query("select * from `link_bin` where `link`='$domain'");
    //if(mysql_num_rows($res))
    sc_query("update `link_bin` set `banned`='yes' where `link`='$domain'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_ban_ref($refer){
    $res=mysql_num_rows(sc_query("select * from banned where `link`='$refer'"));
    if($res==0) sc_query("insert into `banned` (`link`) VALUES ('$refer')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_ban_ip($ip){
    $res=mysql_num_rows(sc_query("select * from banned where `ip`='$ip'"));
    if($res==0) sc_query("insert into `banned` (`ip`) VALUES ('$ip')");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_unban_domain($domain){
    $res=mysql_num_rows(sc_query("select * from banned where `domain`='$domain'"));
    if($res==0) sc_query("delete from `banned` where `domain`='$domain'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_unban_ref($refer){
    sc_query("delete from `banned` where `link`='$refer'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_unban_ip($ip){
    sc_query("delete from `banned` where `ip`='$ip'");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_banned_domain($domain){
    $res=sc_query("select * from `banned` where `domain`='$domain'");
    if(mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_banned_ref($refer){
    $res=sc_query("select * from `banned` where `link`='$refer'");
    if(@mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_banned_ip($ip){
    $res=sc_query("select * from `banned` where `ip`='$ip'");
    if(mysql_num_rows($res)) return true;
    return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
?>