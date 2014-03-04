<?
$title="User Profile";
chdir("../../");
include("header.php");
//////////////////////////////////////////////////////////////////////////////////////
// CHANGE PASSWORD
if($change_password == "yes"){
    echo "<h1>Change Password</h1>\n";
    if(empty($data->name)) {
        echo sc_warn("You must be <a href=$RFS_SITE_URL/login.php>logged in</a> to change your password!");
    }
	
    if( (!empty($data->pass)) &&
			 ( md5($pass1) != $data->pass ) ) {
			echo sc_warn("You did not enter the correct current password!");
			$act="show_password_form";
    }
    else {
		
        if($pass2==$pass3) {			
				if(!empty($pass2)) {
					$pass2=md5($pass2);
					sc_query("update users set pass='$pass2' where name = '$data->name'");
					sc_info("Password changed!","WHITE","GREEN");
					include("footer.php");
					exit;					
				}
				else {
					echo sc_warn("The password can not be empty.");
					$act="show_password_form";
				}
        }
        else {
			echo sc_warn("The passwords do not match!");
			$act="show_password_form";
		}
    }    

}
//////////////////////////////////////////////////////////////////////////////////////
// UPDATE USER DATA
if($_REQUEST['act']=="update") {
	
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

	sc_info("UPDATED PROFILE","WHITE","GREEN");
    
    if(!empty($name)) sc_query("UPDATE users SET `real_name`='$name' where `name` = '$data->name'");
	
    if(!empty($sentence)) {
        $sentence=addslashes($sentence);
        $result = sc_query("UPDATE users SET `sentence`='$sentence' where `name` = '$data->name'");
    }
	
    sc_query("UPDATE users SET `email`='$email' where `name` = '$data->name'");
	$webpage=addslashes($webpage);
    if(!empty($webpage))
		sc_query("UPDATE users SET `webpage`='$webpage' where `name` = '$data->name'");
    if(!empty($website_fav))
		sc_query("UPDATE users SET `website_fav`='$website_fav' where `name` = '$data->name'");
    if(!empty($avatar))
		sc_query("UPDATE users SET `avatar`='$avatar' where `name` = '$data->name'");
    if(!empty($country))
		sc_query("UPDATE users SET `country`='$country' where `name` = '$data->name'");
		
    sc_query("UPDATE users set gender='$gender' where name='$data->name'");
	if(!empty($show_contact_info))
		sc_query("UPDATE users SET show_contact_info='$show_contact_info' where name = '$data->name'");    
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
        sc_query("UPDATE users SET birthday='$der' where name = '$data->name'");
    }
}
$data=sc_getuserdata($data->name);

function pro_nav_bar($data) {    eval(scg());
    
if(sc_access_check("news","edit")) 
	lib_button("$RFS_SITE_URL/modules/news/news.php?action=edityournews","Edit news");
if(sc_access_check("news","submit")) 
	lib_button("$RFS_SITE_URL/modules/news/news.php?showform=yes","Create news");
if(sc_access_check("files","upload"))
	lib_button("$RFS_SITE_URL/modules/files/files.php?action=upload","Upload file");
if(sc_access_check("admin","access"))
	lib_button("$RFS_SITE_URL/admin/adm.php","Admin");

lib_button("$RFS_SITE_URL/modules/profile/profile.php?act=show_password_form","Change password");

    
}

if(empty($data->name))
{
  echo "<p>You must <a href=$RFS_SITE_URL/login.php>login</a> first!</p>\n";
  include("footer.php");
  exit;
}

if($act=="show_password_form") {
    echo "<h2>Change Password</h2>";
    echo "<table border=0><form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/profile/profile.php method=post>\n";
    echo "<input type=hidden name=change_password value=yes>\n";

	if(!empty($data->pass))
		echo "<tr><td>Current Password</td><td><input type=password name=pass1 value=\"\"></td></tr>\n";

    echo "<tr><td>New Password</td><td><input type=password name=pass2></td></tr>\n";
    echo "<tr><td>New Password (again)</td><td><input type=password name=pass3></td></tr>\n";
    echo "<tr><td>&nbsp;</td><td><input type=submit name=submit value=\"Go!\"></td></tr>\n";
    echo "</form></table>\n";

    include("footer.php");
    exit();
}

$nm=$data->real_name;
if(empty($nm)) $nm=str_replace("."," ",$data->name);
echo ucwords("<h1> $nm's $RFS_SITE_NAME profile </h1>");
pro_nav_bar($data);
echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/modules/profile/profile.php\" >";

echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>";
echo "<input type=hidden name=act value=update>\n";
echo "<td> ";
$g=sc_getfiletype($data->avatar);
if(	($g=="gif")||
	($g=="jpg")||
	($g=="png"))
echo "<img src=$data->avatar align=left title=\"$data->sentence\" alt=\"$data->sentence\" width=100>";
if($g=="swf")
flash($data->avatar,100,100);
echo "</td>\n";
if(empty($data->gender)) $data->gender="male";

echo "<td><img src=$RFS_SITE_URL/images/icons/sym_$data->gender.gif border=0 title=\"$data->gender\" alt=\"$data->gender\"></td>\n";
echo "<td><select name=gender><option>$data->gender<option>male<option>female</select></td>";
list($adate,$atime)=explode(" ",$data->first_login );
list($tyear,$tmonth,$tday)=explode("-",$adate);
$dtq=explode(" ",$data->first_login);
$date=explode("-",$dtq[0]);
$time=explode(":",$dtq[1]);
$t=mktime($time[0],$time[1],$time[2],
		  $date[1],$date[2],$date[0]);  // h,s,m,mnth,d,y
$nmonth=date("M",$t);
echo "<td> <i> Member since $nmonth $tday, $tyear </i> </td>\n";
echo "</tr></table>";

//////////////////////////////////////////////////////////////////////////////////////////////////
// status stuff


echo "<table border=0 cellspacing=2 cellpadding=2><tr><td>\n";
echo "<a href=\"javascript:win('helpposts.php')\">News Posts</a>:$data->posts\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpfposts.php')\">Forum Posts</a>:$data->forumposts\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpfreply.php')\">Forum Replies</a>:$data->forumreplies\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpcomments.php')\">Comments</a>:$data->comments\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpuploads.php')\">U/L</a>:$data->files_uploaded\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpdownloads.php')\">D/L</a>:$data->files_downloaded\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpfreply.php')\">Links Added</a>:$data->linksadded\n";
echo "</td><td>\n";
echo "<a href=\"javascript:win('helpref.php')\">Referrals</a>:$data->referrals\n";
echo "</td></tr></table>\n";


//////////////////////////////////////////////////////////////////////////////////////////////////
// profile data start

echo "<table border=0> <tr><td>\n";

echo "<table border=0>\n";
echo "<tr><td>Real Name :</td><td>";
echo "<input type=textbox name=name size=30 value=\"$data->real_name\"> </td>";
echo "<td>&nbsp;</td></tr>\n";


$dtq=explode(" ",$data->birthday);
$years=rfs_user_age($dtq[0]);
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
echo "</select> (<i>$years years old</i>)</td><td> </td></tr>\n";
if(empty($data->country)) $data->country="Select Country";
echo "<tr><td>Country   :</td><td> <select name=country><option>$data->country\n";
sc_countries();
echo "</select> </td><td>&nbsp;</td></tr>\n";
echo "<tr><td>Quote     :</td>";
echo "<td><textarea name=sentence rows=5 cols=50>";
echo $data->sentence;
echo "</textarea>    </td><td>&nbsp;</td></tr>\n";
echo "<tr><td>Email :</td><td> <input type=textbox name=email    size=30 value=\"$data->email\">         </td><td></td></tr>\n";

echo "<tr><td>Personal Webpage:</td><td> <input type=textbox name=webpage  size=30 value=\"$data->webpage\">       </td><td> <a href=$data->webpage target=_blank><img src=$RFS_SITE_URL/images/icons/wp.gif  border=0 alt=\"Visit this person's website!\" title=\"Visit this person's website!\" height=16> </a></td></tr>\n";
echo "<tr><td>Favorite Webpage:</td><td> <input type=textbox name=website_fav  size=30 value=\"$data->website_fav\">       </td><td> <a href=$data->website_fav target=_blank><img src=$RFS_SITE_URL/images/icons/wp.gif  border=0 alt=\"Visit this person's favorite website!\" title=\"Visit this person's favorite website!\" height=16> </a></td></tr>\n";
echo "<tr><td>Avatar:</td><td> <input type=textbox name=avatar size=30 value=\"$data->avatar\"> </td><td> \n";
echo "<a href=$RFS_SITE_URL/modules/files/files.php?action=upload_avatar> \n";
// <img src=$RFS_SITE_URL/images/navigation/dotdotdot.gif border=0 title=\"Upload an avatar!\" alt=\"Upload an swf, gif, or jpg avatar!\"></a>\n";
echo "Upload an swf, gif, or jpg avatar!</a></td></tr>\n";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
echo "<tr><td>Show Contact Info:</td><td><select name=show_contact_info>\n";
if($data->show_contact_info=="yes") echo "<option>yes<option>no";
else echo "<option>no<option>yes";
echo "</select></td><td>&nbsp;</td></tr>\n";

echo "<tr><td align=right>Update</td><td>\n";
echo "<input type=submit name=\"update\" value=\"Go!\">\n";
echo "</td><td>&nbsp;</td></tr>\n";
echo "</table> ";
echo "</td></tr></table>\n";
echo "</form>\n";

/* 
/////////////////////////
// awards - medals
echo "<p>Your Achievments...</p>\n";
echo "<table border=0 cellspacing=0 cellpadding=0 width=100% class=sc_black>\n";
echo "<tr><td>\n";
 sc_putawards($data->name);
echo "</td></tr>\n";
echo "</table>\n";
*/
//////////////////////////////////////////////////////////////////////////////////////////////////
// files start

$q="where submitter='$data->name'";
$l="$filetop,$filelimit";  
$i=0; $bg=0; $filelist=@sc_getfilelist($q,$l);
if(count($filelist)) {
  
	sc_info("Your files...","BLACK","WHITE");
  
	if(sc_access_check("files","upload")) 
		echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=upload\">Upload</a>]";
	if(sc_access_check("files","addlink")) 
		echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=addfilelinktodb\">Add Link</a>]\n";
  
	  echo "<table border=0 cellspacing=0 cellpadding=3 width=100% class=sc_black>\n";
	  echo "<tr bgcolor=$file_header height=16 class=sc_black>\n";
	  echo "<td class=\"sc_black\">Work Safe</td>\n";
	  echo "<td class=\"sc_black\">Type</td>\n";
	  echo "<td class=\"sc_black\">Name</td>\n";
	  echo "<td class=\"sc_black\" width=100>Size</td>\n";
	  echo "<td class=\"sc_black\" width=50>D'lds</td>\n";
	  echo "<td class=\"sc_black\">Category</td>\n";
	  echo "<td class=\"sc_black\">Description &nbsp;</td>\n";
	  echo "<td class=\"sc_black\">&nbsp;</td>\n";
	  echo "<td class=\"sc_black\">&nbsp;</td>\n";
	  echo "</tr>\n";
  
  if(empty($filetop)) 	$filetop=0;
  if(empty($filelimit))	$filelimit=25;
  
  
  while($i<count($filelist)) {
	  
    $filedata=sc_getfiledata($filelist[$i]);
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
    echo "<td class=\"sc_black\">$filedata->size &nbsp;</td>\n";
    echo "<td class=\"sc_black\">$filedata->downloads &nbsp;</td>\n";
    echo "<td class=\"sc_black\">$filedata->category &nbsp;</td>\n";
    echo "<td class=\"sc_black\">$filedata->description &nbsp;</td>\n";
    echo "<td class=\"sc_black\"><a href=$RFS_SITE_URL/modules/files/files.php?action=del&file_mod=yes&id=$filedata->id><img src=$RFS_SITE_URL/images/icons/deletefile_sm.gif alt=\"Delete file!\" title=\"Delete file!\" border=0></a></td>\n";
    echo "<td class=\"sc_black\"><a href=$RFS_SITE_URL/modules/files/files.php?action=mdf&file_mod=yes&id=$filedata->id>modify</a></td>\n";
    echo "</tr>\n";
    $i=$i+1;
    $bg=$bg+1; if($bg>1) $bg=0;
  }
  echo "</table>\n";
  //////////////////
} // end Files

pro_nav_bar($data);

//////////////////////////////////////////////////////////////////////////////////////////////////
include("footer.php");

?>
