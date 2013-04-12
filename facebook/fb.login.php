<?
if(  (array_pop(explode("/",getcwd()))) == "facebook" ) chdir("../");

include_once("include/lib.all.php");
require_once("facebook/src/facebook.php");

sc_query_user_db("ALTER TABLE `users` ADD `facebook_id` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `facebook_username` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `facebook_name` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `first_name` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `last_name` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `facebook_link` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `timezone` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `locale` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `country` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `gender` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `email` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `paypal_email` text NOT NULL;");
sc_query_user_db("ALTER TABLE `users` ADD `first_login` timestamp NOT NULL;");

sc_query_user_db("
CREATE TABLE IF NOT EXISTS `users` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `alias` text COLLATE utf8_unicode_ci NOT NULL,
  `name_shown` text COLLATE utf8_unicode_ci NOT NULL,
  `donated` text COLLATE utf8_unicode_ci NOT NULL,
  `pass` text COLLATE utf8_unicode_ci NOT NULL,
  `real_name` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook_id` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook_name` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `facebook_link` text COLLATE utf8_unicode_ci NOT NULL,
  `timezone` text COLLATE utf8_unicode_ci NOT NULL,
  `locale` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` text COLLATE utf8_unicode_ci NOT NULL,
  `webpage` text COLLATE utf8_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8_unicode_ci NOT NULL,
  `picture` text COLLATE utf8_unicode_ci NOT NULL,
  `posts` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `show_flash` text COLLATE utf8_unicode_ci NOT NULL,
  `website_fav` text COLLATE utf8_unicode_ci NOT NULL,
  `sentence` text COLLATE utf8_unicode_ci NOT NULL,
  `first_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reporter` text COLLATE utf8_unicode_ci NOT NULL,
  `show_contact_info` text COLLATE utf8_unicode_ci NOT NULL,
  `upload` text COLLATE utf8_unicode_ci NOT NULL,
  `files_uploaded` int(11) NOT NULL DEFAULT '0',
  `files_downloaded` int(11) NOT NULL DEFAULT '0',
  `last_activity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '0',
  `forumposts` int(11) NOT NULL DEFAULT '0',
  `forumreplies` int(11) NOT NULL DEFAULT '0',
  `videowall` text COLLATE utf8_unicode_ci NOT NULL,
  `theme` text COLLATE utf8_unicode_ci NOT NULL,
  `referrals` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `linksadded` int(11) NOT NULL DEFAULT '0',
  `logins` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1005 ;
");

// echo "Connecting with facebook...";

$goback=$_GET['goback'];
if(!empty($goback)) $_SESSION['goback']=$goback;
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
        "&scope=user_birthday,read_stream";

    // echo $dialog_url;
    echo("<script> top.location.href='" . $dialog_url . "'</script>");

    }

    if( $_SESSION['state'] &&
       ($_SESSION['state'] === $_REQUEST['state'])         ) {

        $token_url =
         "https://graph.facebook.com/oauth/access_token?".
        "client_id="        . $RFS_SITE_FACEBOOK_APP_ID .
        "&redirect_uri="    . urlencode("$RFS_SITE_URL/facebook/index.php").
        "&client_secret="   . $RFS_SITE_FACEBOOK_SECRET .
        "&code="            . $code;

        // echo $token_url;

        $response = file_get_contents($token_url);

        // echo $response;

        $params = null;
        parse_str($response, $params);

        $_SESSION['access_token'] = $params['access_token'];

        $graph_url =
        "https://graph.facebook.com/me?".
        "access_token=" . $params['access_token'];


        $x=file_get_contents($graph_url);

        $user_profile = json_decode($x);

        if($user_profile->verified==true) {

            // echo " Logged in via Facebook <br>";

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


            $dbg_info="============[FACEBOOK LOGIN ATTEMPT]=======================================<br>".
            "facebook_id :".$facebook_id."<br>".
            "facebook_name :".$facebook_name."<br>".
            "first_name :".$first_name."<br>".
            "last_name :".$last_name."<br>".
            "facebook_link :".$facebook_link."<br>".
            "fname :".$fname."<br>".
            "gender :".$gender."<br>".
            "email :".$email."<br>".
            "timezone :".$timezone."<br>".
            "locale :".$locale."<br>".
            "================================================================================================<br>";

            sc_log($dbg_info);

            // echo "$facebook_id... $facebook_name fetching your account details<br>";
            $r=sc_query_user_db("select * from users where `facebook_id` = '$facebook_id'");
            $user=mysql_fetch_object($r);

            //echo "name: $first_name<br>";
            //echo "last name: $last_name<br>";
            //echo "facebook name: $facebook_name<br>";
            //echo "facebook id: $facebook_id<br>";
            //echo "email: $email<br>";

            if($user->facebook_id!=$facebook_id) {
                    $time1=date("Y-m-d H:i:s");
                    sc_query_user_db("  insert into `users` ( `name`, `facebook_id`, `first_login` )
                                                     VALUES ( '$fname', '$facebook_id', '$time1' ); " );
                    echo "WARNING: a1b2c3<br>";
            }

            $r=sc_query_user_db("select * from users where `facebook_id` = '$facebook_id'");            
            $user=@mysql_fetch_object($r);
			  if(!$user->id) {
                  echo "ERROR 3473. Please report this.<br>\n";
                  exit();
            }

            sc_query_user_db("update users set facebook_name='$facebook_name'   where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set first_name='$first_name'         where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set last_name='$last_name'           where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set facebook_link='$facebook_link'   where `facebook_id`='$facebook_id'");
            // sc_query_user_db("update users set name='$name'                     where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set gender='$gender'                 where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set timezone='$timezone'             where `facebook_id`='$facebook_id'");
            sc_query_user_db("update users set locale='$locale'                 where `facebook_id`='$facebook_id'");

            $r=sc_query_user_db("select * from users  where `facebook_id`='$facebook_id'");
            $user=@mysql_fetch_object($r);
            if($user->id) {
                $_SESSION['valid_user']  = $user->name;
                $_SESSION["logged_in"]  = "true";
                //  echo $_SESSION['goback'];
                if(sc_yes($_SESSION['goback'])) {					
                    sc_gotopage($RFS_SITE_URL);
                    echo "go back<br>";
                    exit();
                } else {

                    // echo "Authenticated<br>";

                    sc_gotopage($RFS_SITE_URL);
                    
                    exit();
                }
            }
        }
    }
}

/*
$r=sc_query_user_db("select * from users where `facebook_id`='$facebook_id'");
$user=@mysql_fetch_object($r);


$r=sc_query_user_db("select * from users where `email` = '$email'");
if($r) {
            $user=mysql_fetch_object($r);
            if($user->facebook_id=="") { // First time visiting with a facebook id, update database
                echo "First visit from facebook... Welcome $first_name.<br>";
                sc_query_user_db("update users set facebook_id='$facebook_id' where `email`='$email'");
                sc_query_user_db("update users set facebook_name='$facebook_name' where `email`='$email'");
                sc_query_user_db("update users set first_name='$first_name' where `email`='$email'");
                sc_query_user_db("update users set last_name='$last_name' where `email`='$email'");
                sc_query_user_db("update users set facebook_link='$facebook_link' where `email`='$email'");
                sc_query_user_db("update users set name='$name' where `email`='$email'");
                sc_query_user_db("update users set gender='$gender' where `email`='$email'");
                sc_query_user_db("update users set timezone='$timezone' where `email`='$email'");
                sc_query_user_db("update users set locale='$locale' where `email`='$email'");
        }
    }

        $r=sc_query_user_db("select * from users where `facebook_id`='$facebook_id'");
        $user=@mysql_fetch_object($r);
        if($user->id) {                
            $_SESSION['valid_user']  = $user->id;
            $_SESSION["logged_in"]  = "true";
            echo $_SESSION['goback'];
            if(sc_yes($_SESSION['goback'])) {
              sc_gotopage($RFS_SITE_URL);
            }
            else {
                echo "Authenticated<br>";
                echo("<script> frame.location.href='".$RFS_SITE_URL."/facebook/indexfb.php'</script>");
                }
            }
        }

    }
    else {
      echo("ERROR 734.");
    }
} else {

    if(!empty($fb_source)) {
        include("indexfb.php");


        //  echo " <META HTTP-EQUIV=\"refresh\" content=\"0;URL= indexfb.php\">";
        // <script> iframe_canvas.location.href='" .$RFS_SITE_URL. "/facebook/indexfb.php'</script>");
        // echo "You're already logged in...";
    }
}

   /*
$config = array();
$config['appId']    = $RFS_SITE_FACEBOOK_APP_ID;
$config['secret']   = $RFS_SITE_FACEBOOK_SECRET;
$config['fileUpload'] = true;
$config['req_perms'] = 'email, user_birthday, id, name, first_name, last_name, link, username';
$facebook = new Facebook($config);
$access_token   =   $facebook->getAccessToken();
$fb_user        =   $facebook->getUser(); 

/*      user information conversion
        sethcoder.com   facebook
===================================================================               
          facebook_id   id            
        facebook_name   name          
           first_name   first_name    
            last_name   last_name     
        facebook_link   link          
                  name  username      
                gender  gender        
                email   email         
              timezone  timezone      
                locale  locale  */
                /*
if($fb_user) {
    $user_profile = $facebook->api('/me');
    
   // echo $user_profile['link'];    
    
}
else{    // redirect the user to login and authorize your application, if necessary
$url = $facebook->getLoginUrl(
array(
'req_perms' => 'email,user_birthday',
'next' => 'http://www.defectiveminds.com/rfs/facebook/'
));

}*/
?>
