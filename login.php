<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

//include("include/lib.all.php");
include("header.php");
if(empty($outpage)) $outpage=$RFS_SITE_URL;

if(empty($action)) $action=$_REQUEST['action'];

/////////////////////////////////////////////////////////////////////
///////// LOGOUT 
if($action=="logout") {
    session_destroy();
    echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$outpage\">";
    exit();
}

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
		$result = sc_query_user_db("select * from users where name = '$userid'");
		if(mysql_num_rows($result) > 0 ){
			echo "<p>Sorry! There is already a user named $userid</p>\n";
			include("footer.php");
			exit;
		}
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK EMAIL IN DB
	$result = sc_query_user_db("select * from users where email = '$email'");
	if(mysql_num_rows($result) > 0 ){
		echo "<p>Sorry! That email is already being used.</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK EMAIL VALIDITY
	if(sc_is_valid_email($email)){
		if(!empty($email)) echo "<p>Email address is invalid!</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// CHECK VALID CHARACTERS IN USERID
	if(sc_is_valid_name($userid)){
		echo "<p>Invalid characters in your userid. Characters allowed are: a-z, A-Z, 0-9, and _ (No spaces)</p>\n";
		include("footer.php");
		exit();
	}
	/////////////////////////////////////////////////////////////////////
	// GENERATE TEMPORARY PASSWORD
	$password=generate_password();
	// create user account, then send an email confirmation
	$time1=date("Y-m-d H:i:s");
	if(empty($gender)) $gender="male";
	if(!empty($userid))
    $md5password=md5($password);
	$result=sc_query("INSERT INTO `users` (`name`,      `pass`, `gender`, `email`, `first_login`)
									   VALUES ('$userid', '$md5password', '$gender', '$email', '$time1');");
	if($result){

		/////////////////////////////////////////////////////////////////////
		// send email to user for confirmation


		$message = "$RFS_SITE_NAME registration.<br>Your information will not be shared or sold.<br>";
		$message.= "Your new user account is: $userid<br>Your new password is: $password<br><br>\n";
		$message.= "Click here to login:";
		$message.="<a href=\"$RFS_SITE_URL/login.php?userid=$userid&password=";
        $message.=urlencode($password);
        $message.="&action=logingo&sd=3\">$RFS_SITE_URL</a><br><br>\n";
		mailgo($email,$message,"New account setup!");

		/////////////////////////////////////////////////////////////////////
		// send email to admins
		$message = "New user named [$userid] $email has joined $RFS_SITE_NAME<br>\n";

		mailgo("$RFS_SITE_ADMIN_EMAIL",$message,"$RFS_SITE_NAME new member: $userid");
		
		echo "<p>Check your email for an automated response email and follow the instructions to continue.</p>";
		echo "<p>Note: Accounts will be purged after 1 week unless confirmed.</p>";
		echo "<a href=$outpage>Continue</a>";
		sc_log("****> $userid joined!");
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

    rfs_echo($RFS_SITE_JOIN_FORM_CODE);

    include("footer.php");
    exit;

}

/////////////////////////////////////////////////////////////////////
///////// LOGIN FORM
if(empty($action))
    $action="loginform";

/////////////////////////////////////////////////////////////////////
///////// LOGIN GO
if($action=="logingo") {

    if(!empty($_GET['userid'])) $userid=$_GET['userid'];
    if(!empty($_POST['userid'])) $userid=$_POST['userid'];

    if(!empty($_GET['password']))  $password=$_GET['password'];
    if(!empty($_POST['password'])) $password=$_POST['password'];

    $password   = md5(urldecode($_REQUEST['password']));



    $r=sc_query_user_db("select * from users");
    $n=mysql_num_rows($r);
    for($i=0;$i<$n;$i++) {
        $u=mysql_fetch_object($r);
        $x=explode(",",$u->alias);
        for($j=0;$j<count($x);$j++) {
            if($x[$j]==$userid) {
                //echo md5($password);
                if($u->pass==md5($password)){
                    $_SESSION["valid_user"] = $userid;
                    $_SESSION["logged_in"]  = "true";
                    $data=sc_getuserdata($userid);
                    sc_setuservar($userid,"last_login",$data->last_activity);
                    sc_setuservar($userid,"last_activity",date("Y-m-d H:i:s"));
                    sc_log("***********************> $data->name logged in!");
                    if(empty($outpage)) $outpage="$RFS_SITE_URL/index.php";
                    sc_gotopage($outpage);
                    exit();
                }
            }
        }
    }

    $result = sc_query_user_db("select * from users where name = '$userid' and pass = '$password'");
    if($result) {
        if(mysql_num_rows($result) > 0){
            $_SESSION["valid_user"] = $userid;
            $_SESSION["logged_in"]  = "true";
            $data=sc_getuserdata($userid);
            sc_setuservar($userid,"last_login",$data->last_activity);
            sc_setuservar($userid,"last_activity",date("Y-m-d H:i:s"));
            sc_log("***********************> $data->name logged in!");
            if(empty($outpage)) $outpage="$RFS_SITE_URL/index.php";
            sc_gotopage($outpage);
            exit();
        }
        else{
            echo "Invalid Login";
            $_SESSION["valid_user"] = "invalid_user";
            sc_log("***********************> $userid [$password] invalid login attempt from ".getenv("REMOTE_ADDR"));
        }
    }
	$action="forgot";
}

/////////////////////////////////////////////////////////////////////
////////// LOGIN FORGOT PASSWORD
if($action=="forgot_go"){
    if(empty($email)) {
        echo "<p>You must enter a valid email address.</p>\n";		
        $action="forgot";
    }
    else{
        $user=sc_getuserdatabyfield("email",$email);
        if(empty($user)) {
				echo "<br><p>That email address is not in our records! You must <a href=login.php?action=regiester>REGISTER</a>!</p><br>\n";
        }
        else{
            $subject="$RFS_SITE_NAME password reset.";
            $message ="Username:$user->name<br>";
            $message.="Password:$user->pass<br>\n";
            $message.="
            <p style=\"color: black\">
            Cick <a href=\"$RFS_SITE_URL/login.php?userid=$user->name&password=$user->pass&action=logingo\">here</a> to login!</p><br>\n";
            mailgo($user->email,$message,$subject);
            echo "<br><p>Password sent to $email</p><p><a href=$outpage>Continue</a><br>\n";
        }
    }
    exit();
}

/////////////////////////////////////////////////////////////////////
////////// LOGIN FORGOT PASSWORD
if($action=="forgot"){
	echo "<p>You forgot your password!</p>";
    echo "<table border=0 cellspacing=0 cellpadding=0>\n";
    echo "<form enctype=application/x-www-form-URLencoded method=post action=\"$site_url/login.php?action=sendpass\">\n";
    echo "<tr>\n";
    echo "<td>Enter email &nbsp;</td>\n";
    echo "<td><input type=textbox name=email>&nbsp;
	<input type=hidden name=outpage value=\"$outpage\"> 	</td>\n";
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
            rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
    }
    include("footer.php");
    exit();
}

include("footer.php");

?>
