<?php
function lib_users_age($birthDay) { // date in yyyy-mm-dd format
	$birthday = explode("-", $birthDay); 
	$age = (date("md", date("U", mktime(0, 0, 0, $birthday[1], $birthday[2], $birthday[0]))) > date("md") ? ((date("Y") - $birthday[0]) - 1) : (date("Y") - $birthday[0]));
	return $age;
}
function lib_users_online() {
	$dt=lib_users_get_data($_SESSION['valid_user']);
	$name=$dt->name;
	if(empty($name)) $name="(Guest)";
	$li="0";
	$data=$GLOBALS['data'];
	if($data->name==$name) $li="1";

	$REMOTE_ADDR=getenv("REMOTE_ADDR");

	// $PHP_SELF=$_SERVER['PHP_SELF'];
	$PHP_SELF=lib_domain_phpself();

	$refer=getenv("HTTP_REFERER");
	$timeoutseconds = 300;
	$timestamp = time();
	$timeout = $timestamp-$timeoutseconds;

	$res=lib_mysql_query("select * from useronline where `ip`='$REMOTE_ADDR'");
	if($res->num_rows) {
		$usro=$res->fetch_object();
		$insert = lib_mysql_query("update useronline set `name`='$name' where `ip`='$REMOTE_ADDR'");
		$insert = lib_mysql_query("update useronline set `timestamp`='$timestamp' where `ip`='$REMOTE_ADDR'");
		$insert = lib_mysql_query("update useronline set `loggedin`='$li' where `ip`='$REMOTE_ADDR'");

	} else {
		$res=lib_mysql_query("select * from useronline where name='$name'");
		if($res->num_rows) {
			$insert = lib_mysql_query("update useronline set timestamp='$timestamp' where name='$name'");
			$insert = lib_mysql_query("update useronline set page='$PHP_SELF' where name='$name'");
			$insert = lib_mysql_query("update useronline set `loggedin`='$li' where `ip`='$REMOTE_ADDR'");
		} else {
			$insert = lib_mysql_query("INSERT INTO useronline
			                   (`timestamp`, `ip`,           `name`,   `loggedin`, `page`)
			                   VALUES ('$timestamp','$REMOTE_ADDR', '$name',  '$li', '$PHP_SELF')"); // '$refer',
		}

		// if(!($insert)) { print "Useronline Insert Failed > "; }
	}

	$delete = lib_mysql_query("DELETE FROM useronline WHERE timestamp<$timeout");
	$result = lib_mysql_query("SELECT DISTINCT ip FROM useronline");
	$user = $result->num_rows;
	return $user;
}
function lib_users_logged_in() {
	$result = lib_mysql_query("SELECT DISTINCT ip FROM useronline WHERE loggedin='1'");
	$user   = $result->num_rows;
	return $user;
}
function lib_users_logged_details() {
	$result = lib_mysql_query("SELECT DISTINCT ip,page,name FROM useronline");
	$nusers=0;
	$user="";
	for($i=0; $i<$result->num_rows; $i++) {
		$usrdata=$result->fetch_object();
		// $usrdata->page=str_replace("/","",$usrdata->page);
		$pg=explode("/",$usrdata->page);
		$upg=$pg[count($pg)-1];
		$user.="$usrdata->name ($upg)";
        
		if(($nusers>1) && ($i<( $nusers-1)))
			$user.="<br>";
	}
	return $user;
}
function lib_users_avatar_code($user) { eval(lib_rfs_get_globals());
	$userdata=lib_users_get_data($user);
	$ret = "<a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$userdata->name\">\n";
	if(empty($userdata->avatar)) $userdata->avatar="$RFS_SITE_URL/images/icons/noimage.gif";
    $g=lib_file_getfiletype($userdata->avatar);
    if(($g=="png") || ($g=="bmp") || ($g=="gif") || ($g=="jpg")) {
        $ret.= "<img src=\"$userdata->avatar\" ";
        $ret.= "title=\"$userdata->sentence\" ";
        $ret.= "alt=\"$userdata->sentence\" width=100 border=0>";
    }
    if($g=="swf") $ret.= lib_flash_embed_code($userdata->avatar,100,100);
    $ret.= "</a>\n";
    return($ret);
}
function lib_users_set_var($name,$var,$set) {
	$set=addslashes($set);
	lib_mysql_query("UPDATE `users` SET `$var`='$set' where name = '$name'");
}
function lib_users_get_name($x){

	$o=$x;
	if(is_numeric($x)) {
		$ur=lib_mysql_query("select * from `users` where id='$x'");
		$u=$ur->fetch_object(); 
		$o=$u->first_name;
	}
	
	
    return $o;
}
function lib_users_create($name,$pass,$e){
    $time1=date("Y-m-d H:i:s");
    $result=lib_mysql_query_user_db("INSERT INTO `users` (`name`, `pass`, `email`, `first_login`) VALUES ('$name', '$pass', '$e', '$time1');");
}
function lib_users_alias($x) {
    $data=lib_users_get_data($_SESSION['valid_user']);
    $ax=explode(",",$data->alias);
    for($j=0;$j<count($ax);$j++) {
        if($ax[$j]==$x) return TRUE;
    }
    return FALSE;
}
function lib_users_get_data($name){
    if(is_numeric($name)){
		$result = lib_mysql_query("select * from `users` where `id` = '$name'");
		$d=$result->fetch_object();
		if(empty($d->name_shown)) $d->name_shown=$d->name;
		return ($d);
	}
    else {
        $r=lib_mysql_query("select * from `users`");
	if($r) {
		while($d=$r->fetch_object()) {
            if($d->name==$name) {
				if(empty($d->name_shown)) $d->name_shown=$d->name;
				return $d;
			}
            $ax=explode(",",$d->alias);
            for($j=0;$j<count($ax);$j++) {
                if($ax[$j]==$name) {
						if(!empty($name)) {
							if(empty($d->name_shown))
								$d->name_shown=$d->name;							
							return $d;
						}
					}
            	}
            }
		}
    }
    return 0;
}
function lib_users_get_databyfield($field,$data){
    $result=lib_mysql_query_user_db("select * from `users` where `$field` = '$data'");
    return $result->fetch_object();
}
function lib_users_add_downloads($user,$points){
  $result = lib_mysql_query("select * from `users` where name = '$user'");
  if($result->num_rows>0){
    $ud=$result->fetch_object();
    $downloads=intval($ud->downloads)+intval($points);
    lib_mysql_query("UPDATE `users` SET files_downloaded=$downloads where name = '$user'");
  }
}
?>