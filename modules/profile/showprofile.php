<?
chdir("../../");
include("header.php");
$user=$_REQUEST['user'];
if(empty($user)) $user=$data->name;
$userdata=sc_getuserdata($user);

echo "<br><h1>$userdata->name's $RFS_SITE_NAME profile</h1>";

echo "<table border=0><tr>";
echo "<td width=10 valign=top><center><a href=\"$RFS_SITE_URL/showprofile.php?user=$userdata->name\">\n";
sc_useravatar($userdata->name);
echo "</a></center></td>\n";

echo "<td>\n";
$gen=$userdata->gender;
echo "<img src=\"$RFS_SITE_URL/images/sym_".$gen.".gif\" border=0 alt=\"$gen\" ><br>\n";

if($userdata->show_contact_info=="yes") {
    
    echo "<a href=\"".sc_getemailcode($userdata->email)."\"><font class=sc_email>$thisemail</font><img src=$RFS_SITE_URL/images/email2.gif border=0 alt=\"E-Mail\" height=16></a><br>\n";
    echo "<a href=$userdata->webpage target=_blank><img src=$RFS_SITE_URL/images/wp.gif  border=0 alt=\"Visit this person's website!\" height=16></a><br>\n";
    echo "<a href=$userdata->webpage target=_blank>$userdata->webpage</a><br>";
    
}
else
{
    $sex="her"; if(($userdata->gender)=="male") $sex="his";
    echo "$userdata->name has $sex contact info hidden <br>\n";
}


echo "News Posts: $userdata->posts<br>\n";
echo "Forum Posts: $userdata->forumposts<br>\n";
echo "Forum Replies: $userdata->forumreplies<br>\n";
echo "Comments: $userdata->comments<br>\n";
echo "Uploads: $userdata->files_uploaded<br>\n";
echo "Downloads: $userdata->files_downloaded<br>\n";
echo "Links Added: $userdata->linksadded<br>\n";
echo "Referrals: $userdata->referrals<br>\n";

echo "Member since: ".sc_time($userdata->first_login)."<br>";
echo "Last seen on: ".sc_time($userdata->last_login)."<br>";

echo "Quote: $userdata->sentence<br>";

if($userdata->show_contact_info=="yes")
{
echo "Real Name: $userdata->real_name<br>";
echo "Country: $userdata->country<br>";
echo "Born: ".b4time($userdata->birthday)."<br>";

$userdata->webpage=str_replace(":","_rfs_colon_",$userdata->webpage);
$userdata->website_fav=str_replace(":","_rfs_colon_",$userdata->website_fav);
echo "Personal Website: <a href=\"$RFS_SITE_URL/link_out.php?link=$userdata->webpage\" target=\"_blank\">$userdata->webpage</a><br>";
echo "Favorite Website: <a href=\"$RFS_SITE_URL/link_out.php?link=$userdata->website_fav\" target=\"_blank\">$userdata->website_fav</a><br>";
}

//echo "Awards: ".sc_getawards($userdata->name)."<br>\n";

echo "</tr></table>\n";



include("footer.php");
?>


