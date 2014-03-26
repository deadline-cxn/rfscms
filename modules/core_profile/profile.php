<?
$title="User Profile";
chdir("../../");
$RFS_LITTLE_HEADER=true;
include("header.php");

//////////////////////////////////////////////////////////////////////////////////////
// UPDATE USER DATA
function profile_action_update() {	
		eval(lib_rfs_get_globals());
		lib_forms_info("UPDATING PROFILE","WHITE","GREEN");
        $f_ext=lib_file_getfiletype($_FILES['favatar']['name']); //  echo "!!!";
		echo $_ext;
	
        $uploadFile=$RFS_SITE_PATH."/images/avatars/".$_FILES['favatar']['name'];
        if(($f_ext=="png") || ($f_ext=="gif")||($f_ext=="jpg")||($f_ext=="swf")) {
			
            $oldname=$_FILES['favatar']['name'];
            if(move_uploaded_file($_FILES['favatar']['tmp_name'], $uploadFile)){
                system("chmod 755 $uploadFile");
                $error="File is valid, and was successfully uploaded. ";
                echo "<P>You sent: ".$_FILES['favatar']['name'].", a ".$_FILES['favatar']['size']." byte file with a mime type of ".$_FILES['favatar']['type'];
                $oldname=$_FILES['favatar']['name'];
                $newname=$data->name.".".$f_ext;
                rename($RFS_SITE_PATH."/images/avatars/".$oldname,$RFS_SITE_PATH."/images/avatars/".$newname);
                echo " stored as [<a href=\"$RFS_SITE_URL/images/avatars/$newname\" target=\"_blank\">$RFS_SITE_URL/images/avatars/$newname</a>]</p>\n";
                // lib_users_set_var($data->name,"avatar","");
				$fu=addslashes("$RFS_SITE_URL/images/avatars/$newname");
				lib_mysql_query("update `users` set `avatar` = '$fu' where `name`='$data->name'");
				$avatar_was_uploaded=true;
            } else {
                $error ="File upload error!";
                echo "File upload error! [";
                echo $_FILES['favatar']['name'];
                echo "][";
                echo $_FILES['favatar']['error'];
                echo "][";
                echo $_FILES['favatar']['tmp_name'] ;
                echo "][";
                echo $uploadFile;
                echo "]\n";
            }
            if(!$error) $error .= "No files have been selected for upload";
			if(!stristr($error,"File is valid"))
				echo "<P>Status: [$error]</P>\n";
        }
        else {
			if(!empty($f_ext))
			echo "<p>Invalid filetype ($f_ext) for an avatar!</p>";			
		}
	
	$name=$_REQUEST['name'];
	$sentence=$_REQUEST['sentence'];
	$email=$_REQUEST['email'];
	$webpage=$_REQUEST['webpage'];
	$website_fav=$_REQUEST['website_fav'];
	$avatar=$_REQUEST['avatar'];
	$country=$_REQUEST['country'];
	$gender=$_REQUEST['gender'];
	$show_contact_info=$_REQUEST['show_contact_info'];
	$birth_year=$_REQUEST['birth_year'];
	$birth_day=$_REQUEST['birth_day'];
	$birth_month=$_REQUEST['birth_month'];

	
    
    if(!empty($name)) lib_mysql_query("UPDATE `users` SET `real_name`='$name' where `name` = '$data->name'");
	
    if(!empty($sentence)) {
        $sentence=addslashes($sentence);
        $result = lib_mysql_query("UPDATE `users` SET `sentence`='$sentence' where `name` = '$data->name'");
    }
	
    lib_mysql_query("UPDATE `users` SET `email`='$email' where `name` = '$data->name'");
	$webpage=addslashes($webpage);
    if(!empty($webpage))
		lib_mysql_query("UPDATE `users` SET `webpage`='$webpage' where `name` = '$data->name'");
    if(!empty($website_fav))
		lib_mysql_query("UPDATE `users` SET `website_fav`='$website_fav' where `name` = '$data->name'");
    
	
	if(!empty($avatar)) {
		if(!$avatar_was_uploaded)
			lib_mysql_query("UPDATE `users` SET `avatar`='$avatar' where `name` = '$data->name'");
	}
    if(!empty($country))
		lib_mysql_query("UPDATE `users` SET `country`='$country' where `name` = '$data->name'");
		
    lib_mysql_query("UPDATE `users` set gender='$gender' where name='$data->name'");
	if(!empty($show_contact_info))
		lib_mysql_query("UPDATE `users` SET show_contact_info='$show_contact_info' where name = '$data->name'");    
    if((!empty($birth_year))&&(!empty($birth_day))&&(!empty($birth_month)) ) {
        $der  = $birth_year;
        $der .="-";
        $birth_month=ltrim($birth_month,"0");
        if($birth_month<10) $der .= "0";
        $der .= $birth_month;
        $der .= "-";
        $birth_day=ltrim($birth_day,"0");
        if($birth_month<10) $der .= "0";
        $der .= $birth_day;
        $der .= " 01:01:01";
        lib_mysql_query("UPDATE `users` SET birthday='$der' where name = '$data->name'");
    }
	profile_action_();
}

function pro_nav_bar($data) {
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","edit")) 
		lib_buttons_make_button("$RFS_SITE_URL/modules/news/news.php?action=edityournews","Edit news");
	if(lib_access_check("news","submit")) 
		lib_buttons_make_button("$RFS_SITE_URL/modules/news/news.php?showform=yes","Create news");
	if(lib_access_check("files","upload"))
		lib_buttons_make_button("$RFS_SITE_URL/modules/files/files.php?action=upload","Upload file");
	if(lib_access_check("admin","access"))
		lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php","Admin");
	lib_buttons_make_button("$RFS_SITE_URL/modules/core_profile/profile.php?action=show_password_form","Change password");
}

if(empty($data->name)) {
  echo "<p>You must <a href=$RFS_SITE_URL/login.php>login</a> first!</p>\n";
  include("footer.php");
  exit;
}

function profile_action_show_password_form() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("profile");
    echo $RFS_ADDON_URL;
    echo "<h2>Change Password</h2>";
    echo "<table border=0><form enctype=application/x-www-form-URLencoded action=$RFS_ADDON_URL method=post>\n";
    echo "<input type=hidden name=action value=change_password>\n";
	if(!empty($data->pass))
		echo "<tr><td>Current Password</td><td><input type=password name=pass1 value=\"\"></td></tr>\n";
    echo "<tr><td>New Password</td><td><input type=password name=pass2></td></tr>\n";
    echo "<tr><td>New Password (again)</td><td><input type=password name=pass3></td></tr>\n";
    echo "<tr><td>&nbsp;</td><td><input type=submit name=submit value=\"Go!\"></td></tr>\n";
    echo "</form></table>\n";
    include("footer.php");
    exit();
}
//////////////////////////////////////////////////////////////////////////////////////
// CHANGE PASSWORD
function profile_action_change_password() {
     eval(lib_rfs_get_globals());
    echo "<h1>Change Password</h1>\n";
    
    if(empty($data->name)) {
        echo lib_forms_warn("You must be <a href=$RFS_SITE_URL/login.php>logged in</a> to change your password!");
    }
    if( (!empty($data->pass)) &&
			 ( md5($pass1) != $data->pass ) ) {
			echo lib_forms_warn("You did not enter the correct current password!");
            profile_action_show_password_form();
            exit();
    }
    else {
        if($pass2==$pass3) {			
				if(!empty($pass2)) {
					$pass2=md5($pass2);
					lib_mysql_query("update `users` set pass='$pass2' where name = '$data->name'");
					lib_forms_info("Password changed!","WHITE","GREEN");
					include("footer.php");
					exit;					
				}
				else {
					echo lib_forms_warn("The password can not be empty.");
					profile_action_show_password_form();
                    exit();
				}
        }
        else {
			echo lib_forms_warn("The passwords do not match!");
			profile_action_show_password_form();
            exit();
		}
    }    
}


function profile_action_() {
	eval(lib_rfs_get_globals());
	profile_show_form();
	include("footer.php");
}

function profile_show_form() {
	eval(lib_rfs_get_globals());
	$data=lib_users_get_data($data->name);
	$nm=$data->real_name;
	
	echo "<div class=forum_box>";
	if(empty($nm)) $nm=str_replace("."," ",$data->name);
	echo ucwords("<h1> $nm's $RFS_SITE_NAME profile </h1>");
	echo "<hr>";
	pro_nav_bar($data);
	echo "<hr>";
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	// status stuff

	echo "<table border=0 cellspacing=2 cellpadding=2><tr><td>\n";
	//echo "<a href=\"javascript:win('helpposts.php')\">News Posts</a>:$data->newsposts\n";
	//echo "</td><td>\n";
	echo "<a href=\"javascript:win('helpfposts.php')\">Forum Posts</a>:$data->forumposts\n";
	echo "</td><td>\n";
	echo "<a href=\"javascript:win('helpfreply.php')\">Forum Replies</a>:$data->forumreplies\n";
	echo "</td><td>\n";
	//echo "<a href=\"javascript:win('helpcomments.php')\">Comments</a>:$data->comments\n";
	//echo "</td><td>\n";
	echo "<a href=\"javascript:win('helpuploads.php')\">Uploads</a>:$data->uploads\n";
	echo "</td><td>\n";
	echo "<a href=\"javascript:win('helpdownloads.php')\">Downloads</a>:$data->downloads\n";
	//echo "</td><td>\n";
	//echo "<a href=\"javascript:win('helpfreply.php')\">Links Added</a>:$data->linksadded\n";
	//echo "</td><td>\n";
	//echo "<a href=\"javascript:win('helpref.php')\">Referrals</a>:$data->referrals\n";
	echo "</td></tr></table>\n";
		
	echo "<hr>";
	

	// lib_ajax($label,$table,$key,$kv,$field,$width,$type)
	// lib_ajax("Avatar","users", "name", "$data->name","avatar", 60, "","admin","access","lib_ajax_callback_image");
	
	lib_ajax("User Name"	,"users","name","$data->name","name",80,"","admin","access","");
	lib_ajax("First Name"	,"users","name","$data->name","first_name",80,"","admin","access","");
	lib_ajax("Last Name"	,"users","name","$data->name","last_name",80,"","admin","access","");
	lib_ajax("Alias"	    ,"users","name","$data->name","alias",80,"","admin","access","");	
	lib_ajax("Shown Name"	,"users","name","$data->name","name_shown",80,"","admin","access","");
	lib_ajax("Email"		,"users","name","$data->name","email",80,"","admin","access");
			
		

	echo "<hr>";
	
	
	echo "<form 
	enctype=\"multipart/form-data\"
	method=\"post\" action=\"$RFS_SITE_URL/modules/core_profile/profile.php\" >"; // enctype=\"application/x-www-form-URLencoded\"

	echo "<table border=0 cellpadding=0 cellspacing=0>";// "<tr><td>";
	
	echo "<input type=hidden name=action value=update>\n";
		
	echo "<tr><td>";
	
	echo lib_users_avatar_code($data->name);
	
	echo "</td>";
	echo "<td>";
	echo "<input type=text name=avatar size=80 value=\"$data->avatar\"><br><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
	echo "<input type=file name=favatar size=30>";
	
	

				
	
	echo "</td></td> \n";
	//echo "<a href=$RFS_SITE_URL/modules/files/files.php?action=upload_avatar> \n";
	// <img src=$RFS_SITE_URL/images/navigation/dotdotdot.gif border=0 title=\"Upload an avatar!\" alt=\"Upload an swf, gif, or jpg avatar!\"></a>\n";
	//echo "Upload an swf, gif, or jpg avatar!</a></td></tr>\n";
	// echo "</table>";
	// echo "</td>\n";
	echo "<tr>";
	if(empty($data->gender)) $data->gender="male";
	echo "<td><img src=$RFS_SITE_URL/images/icons/sym_$data->gender.gif border=0 title=\"$data->gender\" alt=\"$data->gender\"></td>\n";
	echo "<td><select name=gender><option>$data->gender<option>male<option>female</select></td></tr>";
	
	
	echo "<tr>";
	list($adate,$atime)=explode(" ",$data->first_login );
	list($tyear,$tmonth,$tday)=explode("-",$adate);
	$dtq=explode(" ",$data->first_login);
	$date=explode("-",$dtq[0]);
	$time=explode(":",$dtq[1]);
	$t=mktime($time[0],$time[1],$time[2],
			  $date[1],$date[2],$date[0]);  // h,s,m,mnth,d,y
	$nmonth=date("M",$t);
	echo "<td> Member since:</td><td>$nmonth $tday, $tyear</td>\n";
	echo "</tr>";

	echo "<tr><td>Real Name :</td><td>";
	echo "<input type=textbox name=name size=80 value=\"$data->real_name\"> </td>";
	echo "</tr>\n";


	$dtq=explode(" ",$data->birthday);
	$years=lib_users_age($dtq[0]);
	$date=explode("-",$dtq[0]);
	$time=explode(":",$dtq[1]);

	$t=@mktime( $time[0],$time[1],$time[2], $date[1],$date[2],$date[0]); // h,s,m,mnth,d,y
	$tyear=date("Y",$t);
	$tmonth=date("m",$t);
	$nmonth=date("M",$t);
	$tday=date("d",$t);

	echo "<tr><td>Birthday:</td>\n";
	echo "<td>\n";
	echo "<select name=birth_month style=\"width:60;\">\n";
	echo "<option value=$tmonth>$nmonth";
	echo "<option value=1>Jan\n";
	echo "<option value=2>Feb\n";
	echo "<option value=3>Mar\n";
	echo "<option value=4>Apr\n";
	echo "<option value=5>May\n";
	echo "<option value=6>Jun\n";
	echo "<option value=7>Jul\n";
	echo "<option value=8>Aug\n";
	echo "<option value=9>Sep\n";
	echo "<option value=10>Oct\n";
	echo "<option value=11>Nov\n";
	echo "<option value=12>Dec\n";
	echo "</select>\n";
	echo "<select name=birth_day style=\"width:60;\">\n";
	echo "<option>$tday\n";
	$i=1; while($i<32) {
		if($i<10) echo "<option>0$i";
		else      echo "<option>$i";
		$i=$i+1;
	}
	echo "</select>";
	echo "<select name=birth_year style=\"width:80;\">\n";
	echo "<option>$tyear\n";
	$i=1901; while($i<2050) { echo "<option>$i"; $i=$i+1; }
	echo "</select> (<i>$years years old</i>)</td></tr>\n";
	if(empty($data->country)) $data->country="Select Country";
	echo "<tr><td>Country   :</td><td> <select name=country><option>$data->country\n";
	lib_forms_option_countries();
	echo "</select> </td></tr>\n";
	echo "<tr><td>Quote     :</td>";
	echo "<td><textarea name=sentence rows=5 cols=70>";
	echo $data->sentence;
	echo "</textarea> </td></tr>\n";
	echo "<tr><td>Email :</td><td> <input type=text name=email    size=80 value=\"$data->email\"></td></tr>\n";
	echo "<tr><td>Personal Webpage:</td><td> <input type=text name=webpage  size=80 value=\"$data->webpage\"></td></tr>\n";
	echo "<tr><td>Favorite Webpage:</td><td> <input type=text name=website_fav  size=80 value=\"$data->website_fav\"></td></tr>\n";

	echo "<tr><td>Show Contact Info:</td><td><select name=show_contact_info>\n";
	if($data->show_contact_info=="yes") echo "<option>yes<option>no";
	else echo "<option>no<option>yes";
	echo "</select></td></tr>\n";
	echo "<tr><td align=right>Update</td><td>\n";
	echo "<input type=submit name=\"update\" value=\"Go!\">\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "</div>";

}



//////////////////////////////////////////////////////////////////////////////////////////////////
include("footer.php");

/*
 * 
	//////////////////////////////////////////////////////////////////////////////////////////////////
	// files start

	$q="where submitter='$data->name'";
	$l="$filetop,$filelimit";  
	$i=0; $bg=0; $filelist=@module_files_getfilelist($q,$l);
	if(count($filelist)) {
		lib_forms_info("Your files...","BLACK","WHITE");
		if(lib_access_check("files","upload")) 
			echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=upload\">Upload</a>]";
		if(lib_access_check("files","addlink")) 
			echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=addfilelinktodb\">Add Link</a>]\n";
		  echo "<table border=0 cellspacing=0 cellpadding=3 width=100% class=rfs_black>\n";
		  echo "<tr bgcolor=$file_header height=16 class=rfs_black>\n";
		  echo "<td class=\"rfs_black\">Work Safe</td>\n";
		  echo "<td class=\"rfs_black\">Type</td>\n";
		  echo "<td class=\"rfs_black\">Name</td>\n";
		  echo "<td class=\"rfs_black\" width=100>Size</td>\n";
		  echo "<td class=\"rfs_black\" width=50>D'lds</td>\n";
		  echo "<td class=\"rfs_black\">Category</td>\n";
		  echo "<td class=\"rfs_black\">Description &nbsp;</td>\n";
		  echo "<td class=\"rfs_black\">&nbsp;</td>\n";
		  echo "<td class=\"rfs_black\">&nbsp;</td>\n";
		  echo "</tr>\n";
	  
	  if(empty($filetop)) 	$filetop=0;
	  if(empty($filelimit))	$filelimit=25;
	  
	  while($i<count($filelist)) {
		$filedata=module_files_getfiledata($filelist[$i]);
		$colr=$file_color[1];
		if($bg=="1") $colr=$file_color[2];
		echo "<tr bgcolor=#$colr>\n";
		if($filedata->worksafe=="yes")
			echo "<td align=center><img src=$RFS_SITE_URL/images/icons/worksafe.gif border=0 align=center alt=\"Safe for work... Productivity Chart!\" title=\"Safe for work... Productivity Chart!\"></td>\n";
		else
			echo "<td align=center><img src=$RFS_SITE_URL/images/icons/pinkslip.gif border=0 align=center alt=\"Not safe for work... Pink Slip!\" title=\"Not safe for work... Pink Slip!\"></td>\n";
		echo "<td><center><a href=\"$filedata->location\" target=_blank>\n";
		$xp_ext = explode(".",$filedata->location,40);
		$j = count ($xp_ext)-1;  $ext = "$xp_ext[$j]";
		$ftype=strtolower($ext);
		echo "<img src=$RFS_SITE_URL/images/icons/filetypes/$ftype.png border=0 title=\"$filedata->name\" alt=\"$filedata->name\" width=16></a>\n";
		echo "</center></td>\n";
		echo "<td><form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
		echo "<table border=0><tr><td>\n";
		echo "<input name=name value=\"$filedata->name\" size=30>\n";
		echo "</td><td>\n";
		echo "<input type=submit value=rename></td>\n";
		echo "</td></tr></table>\n";
		echo "<input type=hidden name=file_mod value=yes>\n";
		echo "<input type=hidden name=action value=ren>\n";
		echo "<input type=hidden name=id value=$filedata->id>\n";
		echo "</form>\n";
		echo "<td class=\"rfs_black\">$filedata->size &nbsp;</td>\n";
		echo "<td class=\"rfs_black\">$filedata->downloads &nbsp;</td>\n";
		echo "<td class=\"rfs_black\">$filedata->category &nbsp;</td>\n";
		echo "<td class=\"rfs_black\">$filedata->description &nbsp;</td>\n";
		echo "<td class=\"rfs_black\"><a href=$RFS_SITE_URL/modules/files/files.php?action=del&file_mod=yes&id=$filedata->id><img src=$RFS_SITE_URL/images/icons/deletefile_sm.gif alt=\"Delete file!\" title=\"Delete file!\" border=0></a></td>\n";
		echo "<td class=\"rfs_black\"><a href=$RFS_SITE_URL/modules/files/files.php?action=mdf&file_mod=yes&id=$filedata->id>modify</a></td>\n";
		echo "</tr>\n";
		$i=$i+1;
		$bg=$bg+1; if($bg>1) $bg=0;
	  }
	  echo "</table>\n";
	  //////////////////
	} // end Files
	 */
?>