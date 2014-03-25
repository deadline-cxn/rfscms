<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
$action=$_REQUEST['action'];
/////////////////////////////////////////////////////////////////////
///////// LOGOUT 
if($action=="logout") {
	include_once("include/lib.all.php");
	session_destroy();
	$_SESSION=array();
	$outpage=$_REQUEST['outpage'];
	if(empty($outpage)) $outpage=$RFS_SITE_URL;
    echo "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=$outpage\">";
    exit();
}

/////////////////////////////////////////////////////////////////////
///////// LOGIN GO
if($action=="logingo") {
	include_once("include/lib.all.php");
//	include_once("include/lib.modules.php");
//	include_once("include/lib.sitevars.php");
//	include_once("include/lib.log.php");
//	include_once("include/lib.forms.php");
//	include_once("include/lib.rfs.php");
//	include_once("include/lib.domain.php");
	
    if(!empty($_GET['userid'])) $userid=$_GET['userid'];
    if(!empty($_POST['userid'])) $userid=$_POST['userid'];

    if(!empty($_GET['password']))  $password=$_GET['password'];
    if(!empty($_POST['password'])) $password=$_POST['password'];

    $password = urldecode($password);

    $r=lib_mysql_query_user_db("select * from users");
    $n=mysql_num_rows($r);
	
    for($i=0;$i<$n;$i++) {
        $u=mysql_fetch_object($r);
        $x=explode(",",$u->alias);
        for($j=0;$j<count($x);$j++) {
            if($x[$j]==$userid) {
                
                if($u->pass==md5($password)){
						$_SESSION["valid_user"] = $userid;
						$_SESSION["logged_in"]  = "true";
                    $data=lib_users_get_data($userid);
                    lib_users_set_var($userid,"last_login",$data->last_activity);
                    lib_users_set_var($userid,"last_activity",date("Y-m-d H:i:s"));
                    lib_log_add_entry("[LOGIN]: $data->name ($data->email)");
                    if(empty($outpage))
							$outpage="$RFS_SITE_URL/index.php";
                    //lib_domain_gotopage($outpage);
                    exit();
                }	
            }
        }
    }
	

    $result = lib_mysql_query_user_db("select * from `users` where name = '$userid' and pass = '".md5($password)."'");
    
    if(mysql_num_rows($result) > 0){
            $data=lib_users_get_data($userid);
            lib_users_set_var($userid,"last_login",$data->last_activity);
            lib_users_set_var($userid,"last_activity",date("Y-m-d H:i:s"));				
            lib_log_add_entry("[LOGIN]: $data->name ($data->email)");
            if(empty($outpage)) $outpage="$RFS_SITE_URL/index.php";
			
				session_destroy();				
				session_name(str_replace(" ","_",$RFS_SITE_SESSION_ID));
				session_cache_expire(99999);
				session_start();
				
				$_SESSION["valid_user"] = $userid;
				$_SESSION["logged_in"]  = "true";
				echo "Valid login... Please wait, redirecting...";			
				lib_domain_gotopage($outpage);
            exit();
        }
        else{
            lib_forms_info("Invalid Login","WHITE","RED");
            $_SESSION["valid_user"] = "invalid_user";
            lib_log_add_entry("[INVALID LOGIN]: $userid [$password] invalid login attempt from ".getenv("REMOTE_ADDR"));
        }    
	$action="forgot";
}

include("header.php");

if(empty($outpage)) $outpage=$RFS_SITE_URL;
if(empty($action)) $action=$_REQUEST['action'];


/////////////////////////////////////////////////////////////////////
///////// LOGIN (JOIN)
if($action=="join_go") {
	include_once("header.php");
	/////////////////////////////////////////////////////////////////////
	// CHECK SESSION LOGGED IN
	if($_SESSION['logged_in']=="true") {
		echo "You are already a member... Logout if you want to create a new profile.\n";
		include("footer.php");
		exit;
	} else {
		/////////////////////////////////////////////////////////////////////
		// CHECK USERID IN DB
		$result = lib_mysql_query_user_db("select * from `users` where name = '$userid'");
		if(mysql_num_rows($result) > 0 ){
			echo "<p>Sorry! There is already a user named $userid</p>\n";
			include("footer.php");
			exit;
		}
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK EMAIL IN DB
	$result = lib_mysql_query_user_db("select * from `users` where email = '$email'");
	if(mysql_num_rows($result) > 0 ){
		echo "<p>Sorry! That email is already being used.</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK EMAIL VALIDITY
	if(lib_string_check_email($email)){
		if(!empty($email)) echo "<p>Email address is invalid!</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK VALID CHARACTERS IN USERID
	if(lib_string_check_name($userid)){
		echo "<p>Invalid characters in your userid. Characters allowed are: a-z, A-Z, 0-9, and _ (No spaces)</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// GENERATE TEMPORARY PASSWORD
	$password=lib_string_generate_password();
	// create user account, then send an email confirmation
	$time1=date("Y-m-d H:i:s");
	if(empty($gender)) $gender="male";
	if(!empty($userid))
    $md5password=md5($password);
	$result=lib_mysql_query("INSERT INTO `users` (`name`,      `pass`, `gender`, `email`, `first_login`)
									   VALUES ('$userid', '$md5password', '$gender', '$email', '$time1');");
	if($result) {

		/////////////////////////////////////////////////////////////////////
		// send email to user for confirmation

		$message = "$RFS_SITE_NAME registration.\r\nYour information will not be shared or sold.\r\n<hr>Your new user account is: $userid\r\nYour new password is: $password\r\n<hr>";
		$message.= "Click here to login:";
		$message.="<a href=\"$RFS_SITE_URL/login.php?userid=$userid&password=".urlencode($password)."&action=logingo&sd=3\">$RFS_SITE_URL</a>\r\n";

		mailgo($email,$message,"New account setup!");

		/////////////////////////////////////////////////////////////////////
		// send email to admins
		$message = "New user named [$userid] $email has joined $RFS_SITE_NAME<br>\n";
		mailgo("$RFS_SITE_ADMIN_EMAIL",$message,"$RFS_SITE_NAME new member: $userid");
		echo "<p>Check your email for an automated response email and follow the instructions to continue.</p>";
		echo "<p>Note: Accounts will be purged after 1 week unless confirmed.</p>";
		echo "<a href=$outpage>Continue</a>";
		lib_log_add_entry("[REGISTRATION]: $userid ($email) registered");
	}
	else{
		/////////////////////////////////////////////////////////////////////
		// ERROR MESSAGE
		echo "<p>Error while saving your information! Please try again!</p>\n";
	}
	include("footer.php");
	exit();
}

/////////////////////////////////////////////////////////////////////
///////// LOGIN (JOIN)
if($action=="join") {

    lib_rfs_echo($RFS_SITE_JOIN_FORM_CODE);

    include("footer.php");
    exit;

}

/////////////////////////////////////////////////////////////////////
///////// LOGIN FORM
if(empty($action))
    $action="loginform";


/////////////////////////////////////////////////////////////////////
////////// LOGIN FORGOT PASSWORD
if($action=="sendpass"){

    if(empty($email)) {
        echo "<h1>You must enter a valid email address.</h2>\n";
        $action="forgot";
    }
    else{
		$user=lib_users_get_databyfield("email",$email);
       if(empty($user)) {
			echo "<h1>That email address is not in our records! You must <a href=login.php?action=join>REGISTER</a>!</h1>\n";
		}
       else {
			echo "<h1>Sending new password</h1>";
			$newpass=lib_string_generate_password();
			$md5pass=md5($newpass);
			lib_mysql_query("update `users` set pass='$md5pass' where id='$user->id'");
			$subject="$RFS_SITE_NAME password reset.";
           $message ="Username:$user->name<br>";
           $message.="Password:$newpass<br>\n";
           $message.="<p style=\"color: black\">
           Cick <a href=\"$RFS_SITE_URL/login.php?userid=$user->name&password=$user->pass&action=logingo\">here</a> to login!</p><br>\n";
           mailgo($user->email,$message,$subject);
           echo "<br><p>Password sent to $email</p><br>\n";
        }
    }
	include("footer.php");
	exit();
}

/////////////////////////////////////////////////////////////////////
////////// LOGIN FORGOT PASSWORD
if($action=="forgot"){
	echo "<h1>Did you forgot your password?</h1>";
    echo "<table border=0 cellspacing=0 cellpadding=0 style=\"margin: 10px;\">\n";
    echo "<form enctype=application/x-www-form-URLencoded method=post action=\"$site_url/login.php?action=sendpass\">\n";
    echo "<tr>\n";
    echo "<td>Enter email &nbsp;</td>\n";
    echo "<td><input type=textbox name=email size=30>&nbsp;<input type=hidden name=outpage value=\"$outpage\"></td>\n";
    echo "<td><input type=\"submit\" name=\"Get Password\" value=\"Get Password\"></td>\n";
    echo "</form></table>";
	include("footer.php");	
	exit();
}

/////////////////////////////////////////////////////////////////////
///////////////  LOGIN CHECK VALID USER
$hi=$_SESSION["valid_user"];
if(($hi=="invalid_user")||($join=="true")||(empty($hi))){
    if(($hi=="invalid_user") || (empty($hi))  )
        if($join!="true") {
            echo "<p>Invalid username or password! </p>";
            echo "Did you <a href=\"$site_url/login.php?action=forgot&outpage=$outpage\">forget</a>?</p>\n";
            lib_rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
    }
    include("footer.php");
    exit();
}

include("footer.php");

?>
