<?php
@session_name(str_replace(" ","_","R_F_S_INSTALLER"));
@session_cache_expire(99999);
@session_start();

$RFS_SITE_OS="X";
$RFS_SITE_PATH_SEP="/";
if(substr(PHP_OS,0,3)=="WIN") {
	$RFS_SITE_PATH_SEP="\\";
	$RFS_SITE_OS="Windows";
}
function install_log($x) {
		$fp=fopen("install.log","a"); 
		fwrite($fp,$x."\n");
		fclose($fp);
}
function install_mysql_open_database($address,$user,$pass,$dbname) {
	$mysqli=new mysqli($address,$user,$pass,$dbname);
	if($mysqli->connect_errno) {
        echo "MySQL failed to connect (".$mysqli->connect_errno.") ".$mysqli->connect_error."<br>";
    }
	return $mysqli;
}
function install_mysql_query($query) {
	$msql=install_mysql_open_database($GLOBALS['authdbaddress'],$GLOBALS['authdbuser'],$GLOBALS['authdbpass'],$GLOBALS['authdbname']);
	install_log($query);
	return mysqli_query($msql,$query);
}

$RFS_SITE_PATH = getcwd();
$cwdx=explode($RFS_SITE_PATH_SEP,$RFS_SITE_PATH);
$installd=array_pop($cwdx);
$RFS_SITE_PATH=join($RFS_SITE_PATH_SEP,$cwdx);
if($installd!="install") {
	$RFS_SITE_PATH.=$RFS_SITE_PATH_SEP.$installd;
	chdir("install");
}

$RFS_SITE_URL  = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$hostx=explode("/",$RFS_SITE_URL);
$page=array_pop($hostx);
$folder=array_pop($hostx);
$RFS_SITE_URL=join("/",$hostx);
if($folder!="install") $RFS_SITE_URL.="/".$folder;

$table_width="85%";

include("../include/version.php");
$RFS_BUILD=file_get_contents("../build.dat");
$rver=file_get_contents("https://raw.github.com/sethcoder/rfscms/master/include/version.php");
$rbld=file_get_contents("https://raw.github.com/sethcoder/rfscms/master/build.dat");
$rverx=explode("\"",$rver);
if(empty($rverx[1])) $rverx[1]=0;
if(empty($rbld)) $rbld=0;

if((intval($RFS_BUILD)<intval($rbld))) {
echo "
<div width=100% style='font-size: 23px; background-color: red; color:white;'> 
ATTENTION: NEW VERSION AVAILABLE: \n".$rverx[1]." BUILD $rbld. Get it at 
<a href=\"https://www.github.com/sethcoder/rfscms/\">Github</a>.</div>";
}

echo "<html><head><title>RFS CMS $RFS_VERSION Installer</title>
<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/install/install.css\" type=\"text/css\">
</head><body style='
background-image: url(\"$RFS_SITE_URL/install/install.bkg.jpg\");
background-width: 100%;
background-height: 100%;
background-repeat: no-repeat;
background-attachment: fixed;'>
<div width=100% style='background-color: blue; color:white;'>
RFSCMS $RFS_VERSION Installation.
Go to <a href=\"https://www.rfscms.org/modules/core_wiki/wiki.php\" target=_blank>https://rfscms.com/</a> for more support.
</div>
";

$rfs_password="";
$rfs_password_c="";
$rfs_site_url="";
$rfs_db_password="";
$rfs_db_password_confirm="";
foreach( $_REQUEST as $k => $v ) { if(stristr($k,"rfs_")) { $GLOBALS["$k"]=$v; } }

if(file_exists("$RFS_SITE_PATH/config/config.php")) {
	include_once("$RFS_SITE_PATH/config/config.php");
	$r=install_mysql_query("select * from site_vars where `name`='name'");
	if(!$r) echo "Can't connect to MySQL! Check database configuration settings. \n";
	$sv=$r->fetch_object();
	if(!empty($sv->name)) {	
		echo "<center> <p></p><p></p><table border=0 width=$table_width><tr><td class=formboxd><center><h1> RFS CMS $RFS_VERSION ( Build: $RFS_BUILD)</h1></center></td></tr></table><table border=0 width=$table_width><tr><td class=formboxd><br>";	
		echo "<div style='color: white; background-color: red; '>";
		echo "Whoops... ($sv->name = $sv->value) is already defined in the database.<br>";
		echo "If you want to reinstall RFSCMS, you must first either:<br>";
		echo "1) remove $RFS_SITE_PATH/config/config.php<br>";
		echo "2) drop table $authdbname and recreate it<br>";
		echo "</div>";
		echo "</td></tr></table>";
		exit();
		}
}


$action=""; if(isset($_REQUEST['action'])) $action=$_REQUEST['action'];
if(($action=="go_install") || (empty($action)) ) {

	system("rm install.log");
	
echo "<center> <p></p><p></p>
<table border=0 width=$table_width><tr><td class=formboxd>
<center><h1> RFS CMS $RFS_VERSION ( Build: $RFS_BUILD)</h1></center>
</td></tr></table>
<table border=0 width=$table_width><tr><td class=formboxd>
<br>
<p align=center>Really Frickin Simple Content Management System (RFSCMS) $RFS_VERSION Installation.</p>
<p style=\"padding: 20px;\">
Thank you for deciding to try out RFSCMS. I have put a lot of hard work into making this over the years. My goal was to make the best possible CMS, while at the same time making it easy to use. If you have any questions or comments, or would like to make suggestions for further releases of RFS, or if you would like to help develop this open source CMS, please contact me.<br><br>
<p align=center><a href='mailto:defectiveseth@gmail.com' target=_blank>Email me!<br>
<img src=\"$RFS_SITE_URL/images/icons/email.gif\"></a><br>Follow me on Twitter!<br><br>
<a href=\"https://twitter.com/sethcoder\" class=\"twitter-follow-button\" data-show-count=\"true\" data-show-screen-name=\"false\"> </a>
<script>!function(d,s,id)
{var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id))
{js=d.createElement(s);js.id=id;
js.src=\"//platform.twitter.com/widgets.js\";
fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");
</script>
</p>
<p align=center>
<a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WP3BJCCH5P8N2' target=_blank>Donate!<br><img src=\"$RFS_SITE_URL/images/icons/paypal.gif\"></a>
</p>
<br></td></tr></table>
<table width=$table_width><tr><td class=formboxd><center>
<br>
<a href=$RFS_SITE_URL/install/install.php?action=step_a&submit=Begin>BEGIN!<br><img src=\"$RFS_SITE_URL/images/icons/next.png\"></a>
<br>&nbsp;<br>
</td></tr></table>
</center>
";
}

if($action=="step_b") {
    
    #$if(empty($rfs_udb_password)) $rfs_udb_password = "";
    #if(empty($rfs_udb_password_confirm)) $rfs_udb_password_confirm = "";
        

if(     ($rfs_db_password   !=  $rfs_db_password_confirm) ||
        ($rfs_password      !=  $rfs_password_c)                        ) {
			
        echo "<div width=100% style='background-color: red; color:white;'>CAN NOT PROCEED: THE PASSWORDS DID NOT MATCH!</div>";
        $action="step_a";
	}
	else {
	
		echo "<div width=100% style='background-color: cyan; color: black;'>Attempting to create $RFS_SITE_PATH/config/config.php</div>";
		
		// attempt to chmod install and config folder
		install_log(system("sudo chmod 777 $RFS_SITE_PATH/config"));
		install_log(system("sudo chmod 777 $RFS_SITE_PATH/install"));
				
        $fp=fopen("$RFS_SITE_PATH/config/config.php","wt");
		if($fp) {
			fwrite($fp, "<?php\n");
			fwrite($fp, "////////////////////////////////////////////////////////////\n");
			fwrite($fp, "// RFSCMS https://www.rfscms.org/ \n");
			fwrite($fp, "\$GLOBALS['authdbname']    = \"$rfs_db_name\"; \n");
			fwrite($fp, "\$GLOBALS['authdbaddress'] = \"$rfs_db_address\"; \n");
			fwrite($fp, "\$GLOBALS['authdbuser']    = \"$rfs_db_user\"; \n");
			fwrite($fp, "\$GLOBALS['authdbpass']    = \"$rfs_db_password\"; \n");
			fclose($fp);			
		} else {
			echo "<div width=100% style='background-color: red; color:white;'>ERROR creating $RFS_SITE_PATH/config/config.php!!</div>";
            $action="step_a";
			install_log("Can't open config.php for writing!");
		}

        if(file_exists("$RFS_SITE_PATH/config/config.php")) {

            echo "<div width=100% style='background-color: green; color:white;'>$RFS_SITE_PATH/config/config.php created</div>";

            $GLOBALS["authdbname"]    = $rfs_db_name;
            $GLOBALS["authdbaddress"] = $rfs_db_address;
            $GLOBALS["authdbuser"]    = $rfs_db_user;
            $GLOBALS["authdbpass"]    = $rfs_db_password;

			echo $GLOBALS["authdbname"]." DB IN USE <BR>";
			
			///////////////////////////////////////////////////////////////////////////////
			// UPDATE DATABASE install.sql
			
			$qx=explode(";",file_get_contents("$RFS_SITE_PATH/install/install.sql"));
			for($i=0;$i<count($qx);$i++) {
				$q=$qx[$i];
				// echo "$q; <br><hr>";
				install_mysql_query("$q;");
			}
			
			
			$rfs_password_m=md5($rfs_password);			
			
			$q=" INSERT INTO `users` (`name`,`pass`,`real_name`,`email`,`access_groups`,`theme`)
				VALUES('$rfs_admin', '$rfs_password_m', '$rfs_admin_name', '$rfs_admin_email', 'Administrator', 'default'); ";
			
			// echo $q."<br>";
			install_mysql_query($q);
			
			///////////////////////////////////////////////////////////////////////////////
			// CHECK DATABASE
			
			$r=install_mysql_query("select * from users");
			$n=0;
			if($r) $n=$r->num_rows;
			if(!$n) {
				echo "<div width=100% style='background-color: red; color:white;'>Database error! database: $rfs_db_name, $rfs_db_address, $rfs_db_user, $rfs_db_password </div>";
				$action="step_a";
			} else {
				echo "<div width=100% style='background-color: green; color:white;'>Database activated... Adding RFSCMS data.</div>";
				
			///////////////////////////////////////////////////////////////////////////////
			// UPDATE DATABASE
			
			install_mysql_query("
			INSERT INTO `site_vars` (`name`, `value`) VALUES
			('path', '$RFS_SITE_PATH'),
			('url',  '$RFS_SITE_URL'),
			('name', '$rfs_site_name');");
			
			///////////////////////////////////////////////////////////////////////////////
			// UPDATE DATABASE install.data.sql
			
			$f="$RFS_SITE_PATH/install/install.data.sql";
			$qx=explode("-;-",file_get_contents($f));
			for($i=0;$i<count($qx);$i++) {
				$q=$qx[$i];
				// echo "$q<br><hr>";
				install_mysql_query("$q;");
			}
					
			///////////////////////////////////////////////////////////////////////////////
			// Make system folders
			
			install_log(system("mkdir $RFS_SITE_PATH/logs"));
			install_log(system("mkdir $RFS_SITE_PATH/files"));
			install_log(system("mkdir $RFS_SITE_PATH/files/pictures"));
			install_log(system("mkdir $RFS_SITE_PATH/images"));
			install_log(system("mkdir $RFS_SITE_PATH/images/avatars"));
			


echo "
<center>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border=0 width=$table_width>
<tr><td class=formboxd><center>
<img src=\"$RFS_SITE_URL/images/icons/thumbup.png\">
<p>Congratulations, Your site is configured!</p>
<p>You should now delete the install folder, 
unless you want malicious stuff to happen.</p>	
<p>The page should redirect in 5 seconds, if it
doesn't click here <a href=$RFS_SITE_URL>$RFS_SITE_URL</a>
to begin customizing</p>
<p> &nbsp; </p>
</center>
</td></tr></table>
<META HTTP-EQUIV=\"refresh\" content=\"5;URL=$RFS_SITE_URL\">";
            }
        }
        else {
            echo "<div width=100% style='background-color: red; color:white;'>Can not create $RFS_SITE_PATH/config/config.php make sure $RFS_SITE_PATH/config folder permission is set to 777</div>";
            $action="step_a";
        }
	}
}


if($action=="step_a") {
    echo "<div width=100% style='background-color: green; color:white;'>Detected file path: $RFS_SITE_PATH</div><div width=100% style='background-color: green; color:white;'>Detected domain name: $RFS_SITE_URL</div>";
	if(empty($rfs_site_name))   $rfs_site_name="My Cool RFS Site!";
	if(empty($rfs_site_path))   $rfs_site_path=$RFS_SITE_PATH;
	if(empty($rfs_db_name))     $rfs_db_name="rfs_cms";
	if(empty($rfs_db_address))  $rfs_db_address="localhost";
	if(empty($rfs_db_user))     $rfs_db_user="rfs_cms_user";
	if(empty($rfs_admin))       $rfs_admin="Your_User_Name";
	if(empty($rfs_admin_name))  $rfs_admin_name="Your Real Name";
	if(empty($rfs_country))     $rfs_country="Country of your home";
	if(empty($rfs_admin_email)) $rfs_admin_email="youremail@youremaildomain.what";
    

echo "
<center> <p></p><p></p><table border=0 width=$table_width><tr><td class=formboxd>

<img src=\"$RFS_SITE_URL/images/icons/infobox.png\" style=\"float: right;\"> 
<p style=\"vertical-align: center;\"><br>
<center><h1>Enter your information</h1></center>
</p>
</td></tr></table>
<table width=$table_width><tr><td class=formboxd><center>
<table width=100%><tr><td><p></p></td></tr></table>
<table width=100%>

<tr>
<td>
<form action=\"install.php\" method=\"POST\" enctype=\"application/x-www-form-URLencoded\"></td>                <td>                    <input type=hidden name=action value=step_b>
</td>
</tr>

<tr>
<td>Name of your site (not domain name)</td>
<td><input size=100 type=\"text\" name=\"rfs_site_name\" value=\"$rfs_site_name\"></td>
</tr>

<tr>
<td>Your Username for the site</td>
<td><input size=100 type=\"text\" name=\"rfs_admin\" value=\"$rfs_admin\"></td>
</tr>

<tr>
<td>Your password for the site</td>
<td><input size=100 type=\"password\" name=\"rfs_password\" value=\"$rfs_password\"></td>
</tr>

<tr>
<td>Your password for the site (confirm)</td>
<td><input size=100 type=\"password\" name=\"rfs_password_c\" value=\"$rfs_password_c\"></td>
</tr>

<tr>
<td>Your Real Name</td>
<td><input size=100 type=\"text\" name=\"rfs_admin_name\" value=\"$rfs_admin_name\"></td>
</tr>

<tr>
<td>Your email address</td>
<td><input size=100 type=\"text\" name=\"rfs_admin_email\" value=\"$rfs_admin_email\"></td>
</tr>

<tr>
<td>Working directory</td>
<td><input size=100 type=\"text\" name=\"rfs_site_path\" value=\"$rfs_site_path\"></td>
</tr>

<tr>
<td>Database Name
<a href=\"$rfs_site_url/phpmyadmin/\" target=_blank>Set up Database</a>
</td>
<td><input size=100 type=\"text\" name=\"rfs_db_name\" value=\"$rfs_db_name\"></td>
</tr>

<tr>
<td>Database Address</td>
<td><input  size=100 type=\"text\" name=\"rfs_db_address\" value=\"$rfs_db_address\"></td>
</tr>

<tr>
<td>Database User</td>
<td><input  size=100 type=\"text\" name=\"rfs_db_user\" value=\"$rfs_db_user\"></td>
</tr>

<tr>
<td>Database Password</td>
<td><input size=100 type=\"password\" name=\"rfs_db_password\" value=\"$rfs_db_password\"></td>
</tr>

<tr>
<td>(Confirm Password)</td>
<td><input size=100 type=\"password\" name=\"rfs_db_password_confirm\" value=\"$rfs_db_password_confirm\"></td>
</tr>

<tr>
<td></td>
<td><input type=submit name=submit value=\"Proceed\">
</form>
</td>
</tr>

</table>
</td>
</tr>
</table>
</center> 
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

";
}
