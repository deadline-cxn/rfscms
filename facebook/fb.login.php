<?
if(  (array_pop(explode("/",getcwd()))) == "facebook" ) chdir("../");
include_once("include/lib.all.php");
require_once("facebook/src/facebook.php");
$goback=$_GET['goback'];
$retpage=$_GET['retpage'];
if(!empty($goback)) $_SESSION['goback']=$goback;
if(!empty($retpage)) $_SESSION['retpage']=$retpage;
if(!empty($fb_source)){ $_SESSION['goback']=""; $goback=""; }
if(!$_SESSION['valid_user']) {
    $code = $_REQUEST["code"];
    if(empty($code)) {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $dialog_url =
        "https://www.facebook.com/dialog/oauth?".
        "client_id=" . $RFS_SITE_FACEBOOK_APP_ID .
        "&redirect_uri=" . urlencode("$RFS_SITE_URL/facebook/index.php").
        "&state=" . $_SESSION['state'] .
        "&scope=email,user_birthday,read_stream"; // define vars to get
        echo("<script> top.location.href='" . $dialog_url . "'</script>");
    }
    if( $_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state']) ) {
        $token_url =
		"https://graph.facebook.com/oauth/access_token?".
        "client_id="        . $RFS_SITE_FACEBOOK_APP_ID .
        "&redirect_uri="    . urlencode("$RFS_SITE_URL/facebook/index.php").
        "&client_secret="   . $RFS_SITE_FACEBOOK_SECRET .
        "&code="            . $code;
        $response = file_get_contents($token_url);
        $params = null;
        parse_str($response, $params);
        $_SESSION['access_token']=$params['access_token'];
        $graph_url="https://graph.facebook.com/me?access_token=".$params['access_token'];
        $x=file_get_contents($graph_url);
        $user_profile = json_decode($x);
        if($user_profile->verified==true) {			
			// foreach($user_profile as $k => $v ) {  lib_log_add_entry("   $k [$v] <br>"); }
            $facebook_id    = $user_profile->id;
            $facebook_name  = $user_profile->name;
            $first_name     = $user_profile->first_name;
            $last_name      = $user_profile->last_name;
            $facebook_link  = $user_profile->link;
            $fname          = $user_profile->username;
            $gender         = $user_profile->gender;
            $email          = $user_profile->email;
            $timezone       = $user_profile->timezone;
            $locale         = $user_profile->locale;
			$dbg_info="<span style='background-color:blue; color:white;'>[LOGIN (FACEBOOK)]: $fname "; if(!empty($email)) $dbg_info.="($email)"; $dbg_info.="</span>";
            lib_log_add_entry($dbg_info);
            $r=lib_mysql_query("select * from `users` where `facebook_id` = '$facebook_id'");
            $user=$r->fetch_object();
            if($user->facebook_id!=$facebook_id) {
                    $time1=date("Y-m-d H:i:s");
                    lib_mysql_query("  insert into `users` ( `name`, `facebook_id`, `first_login` ) VALUES ( '$fname', '$facebook_id', '$time1' ); " ); // echo "WARNING: a1b2c3<br>";
            }
            $r=lib_mysql_query("select * from `users` where `facebook_id` = '$facebook_id'");
            $user=$r->fetch_object();
			  if(!$user->id) {
                  echo "ERROR 3473. Please report this immediately.<br>\n";
                  exit();
            }
            lib_mysql_query("update `users` set facebook_name='$facebook_name'   where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set first_name='$first_name'         where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set last_name='$last_name'           where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set facebook_link='$facebook_link'   where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set gender='$gender'                 where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set timezone='$timezone'             where `facebook_id`='$facebook_id'");
            lib_mysql_query("update `users` set locale='$locale'                 where `facebook_id`='$facebook_id'");
            $r=lib_mysql_query("select * from `users`  where `facebook_id`='$facebook_id'");
            $user=$r->fetch_object();
            if($user->id) {
                $_SESSION['valid_user']  = $user->name;
                $_SESSION["logged_in"]  = "true";
                if(lib_rfs_bool_true($_SESSION['goback'])) {
						$retpage=$_SESSION['retpage'];
						if(!empty($retpage)) {
							lib_domain_gotopage($retpage);
							$_SESSION['retpage']="";
						}
						else {
							lib_domain_gotopage($RFS_SITE_URL);
						}					
                    exit();
                } else {
						$retpage=$_SESSION['retpage'];
						if(!empty($retpage)) {
							lib_domain_gotopage($retpage);
							$_SESSION['retpage']="";
						}
						else {
							lib_domain_gotopage($RFS_SITE_URL);
						}
                    exit();
                }
            }
        }
    }
}
?>