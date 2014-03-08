<?
function lib_string_convert_smiles($text) {
	$query = "select * from smilies";
	$smiley_result = lib_mysql_query($query);
	$num_smilies=mysql_num_rows($smiley_result);
	if($num_smilies>0) {
		for($i=0; $i<$num_smilies; $i++) {
			$der = mysql_fetch_array($smiley_result);
			$from=$der['sfrom'];
			$to=$der['sto'];
			$text=str_replace($from,$to,$text);
		}
	}
	$data=$GLOBALS['data'];
	$text=str_replace("[site_name]" ,$GLOBALS['site_name'],$text);
	$text=str_replace("[usr]" ,"Users Online :".lib_users_online($data->name),$text);
	$text=str_replace("[usrs]","Users Logged In :".lib_users_logged_in(),$text);
	$text=str_replace("[lib_users_logged_details]",lib_users_logged_details($data->name),$text);
	$text=str_replace("-{","[",$text);
	$text=str_replace("}-","]",$text);
	return lib_rfs_get($text);
}
function lib_string_check_email($field) {
	$pattern ="/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";
	if(preg_match($pattern, $field)) return false;
	return true;
}
function lib_string_check_name($field) {
	$pattern ="/^([a-zA-Z0-9])+/";
	if(preg_match($pattern, $field)) return false;
	return false;
}
function lib_string_truncate($str,$max_len) {
	if(strlen($str) > $max_len ) {
		$str = substr(trim($str),0,$max_len);
		$str = $str.'...';
	}
	return $str;
}
function lib_string_generate_password() {
	$i=0;
	$password="";
	srand((double) microtime() * 1000000);
	while($i<10) {
		$password .= chr(rand(33,122)+1);
		$i=$i+1;
	}
	$password=str_replace("'","1",$password);
	$password=str_replace("`","2",$password);
	$password=str_replace("\\","3",$password);
	$password=str_replace("\"","4",$password);
	$password=str_replace("&","5",$password);
	$password=str_replace("<","6",$password);
	$password=str_replace(">","7",$password);
	return $password;
}
function lib_string_current_time($whattime) { // 0000-00-00 00:00:00
	$dtq=explode(" ",$whattime);
	$date=explode("-",$dtq[0]);
	$time=explode(":",$dtq[1]);
	$t=mktime( intval($time[0]),intval($time[1]),intval($time[2]),
	           intval($date[1]),intval($date[2]), intval($date[0]) );  // h,s,m,mnth,d,y
	return date("M d, Y @ h:i:s a",$t);
}
function lib_string_number_to_text($x) { 
	$txt=$x." "; if($x==0) $txt=" 0 "; if($x>0) { 	$txt="%2B".$x." "; }
	if($x>1000) { $t=round($x/1000,1); $txt="%2B".$t."k "; }
	if($x>1000000) { $t=round($x/1000000,1); $txt="%2B".$t."m "; }
	return $txt;
}
function lib_string_hex_to_rgb($hex) {
	$hex=str_replace("#","",$hex);
	if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return $rgb;
}
function lib_string_generate_long_uid($y) {
	global $data;
	$x=time().".".md5($y.$data->name.lib_string_generate_password());
	return $x;
}
function lib_string_generate_uid($y) {
	global $data;
	$x=substr(time(),5,5).".".substr(md5($y.$data->name.lib_string_generate_password()),0,3);
	return $x;
}

?>