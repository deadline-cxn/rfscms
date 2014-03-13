<?
///////////////////////////////////////////////////////////////////////////////
// RFS Wiki (Really Frickin Simple)
// By Seth Parson http://www.SethCoder.com/
$title="Wiki ".$_REQUEST['name'];

chdir("../../");
include_once("include/lib.all.php");
if(empty($RFSW_BULLET_IMAGE))
	$RFSW_BULLET_IMAGE	= $RFS_SITE_URL."/modules/wiki/images/bullet.gif";
if(empty($RFSW_LINK_IMAGE))
	$RFSW_LINK_IMAGE= $RFS_SITE_URL."/modules/wiki/images/link2.png";
if(empty($RFS_SITE_PATH)) $RFS_SITE_PATH = getcwd();
if(!empty($rfsw_header)) include($rfsw_header);
$rfsw_admin_mode="false";
if(lib_access_check("wiki","admin"))
	$rfsw_admin_mode="true";
///////////////////////////////////////////////////////////////////////////////
// DO NOT MODIFY BELOW THIS LINE
///////////////////////////////////////////////////////////////////////////////
if(!empty($authdbname))     $rfsw_dbname       = $authdbdbname;
if(!empty($authdbaddress))  $rfsw_address      = $authdbaddress;
if(!empty($authdbuser))     $rfsw_user         = $authdbuser;
if(!empty($authdbpass))     $rfsw_pass         = $authdbpass;
if(!function_exists('rfs_query')) {
    function rfs_query($query)  {
        $address = $GLOBALS['rfsw_address'];
        $user    = $GLOBALS['rfsw_user'];
        $pass    = $GLOBALS['rfsw_pass'];
        $dbname  = $GLOBALS['rfsw_dbname'];
        $mysql=mysql_connect($address, $user, $pass);
    	mysql_select_db($dbname, $mysql);
        return mysql_query($query,$mysql);
    }
}
function rfs_time($whattime){
	// 0000-00-00 00:00:00
	$dtq=explode(" ",$whattime);
	$date=explode("-",$dtq[0]);
	$time=explode(":",$dtq[1]);
	$t=@mktime($time[0],$time[1],$time[2],
    $date[1],$date[2],$date[0]);  // h,s,m,mnth,d,y
    return date("M d, Y @ h:i:s a",$t);
}
if($give_file=="yes"){
    if($rfsw_admin_mode=="true")    {
        $file=$_FILES[$fname]['name'];
        $uploadFile="$short_name";
        move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile);
        echo "Uploaded $uploadFile";
    }
}
if(empty($name)) $name="home";
$name=ucwords($name);
lib_rfs_echo("<h1>$name</h1>");

if($action=="history") {
	echo "<hr>";
	echo "$name history: <br>";
	echo "<hr>";
	$r=lib_mysql_query("select * from wiki where name='$name'");
	for($i=0;$i<mysql_num_rows($r);$i++){
		echo "<div class=\"forum_box\">";
		$wpage=mysql_fetch_object($r);
		echo "<a href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?action=viewpagebyid&id=$wpage->id\">$wpage->name</a> ";
		echo "REVISION: $wpage->revision ";
		if(empty($wpage->revised_by)) $wpage->revised_by=$wpage->author;
		echo "by $wpage->revised_by <br>";
		echo "REVISION NOTE: $wpage->revision_note";
		echo "</div>";
	}
}


echo "<div class=\"wikitext\">";
//////////////////////////////////////////////////////////////////////////////
// backtrace this link
if($action=="editname") {
    lib_mysql_query("update wiki set name='$nname' where name='$name'");
   	$res = rfs_query(" 	select * from wiki where `text` like '%[$name]%' or `text` like '%,$name]%' order by name asc" ); 
    $npg=@mysql_num_rows($res);    
    for($ni=0;$ni<$npg;$ni++) {
        $pg=mysql_fetch_object($res);
    $pg->text=str_ireplace("[$name]","[$nname]",$pg->text);
    $pg->text=str_ireplace("\@$name,","\@$nname,",$pg->text);
        lib_mysql_query("update wiki set text='$pg->text' where name='$pg->name'");
    }
    echo "Name is changed, and links have been updated throughout $npg wiki pages.<br>";
    $action="edit"; $name=$nname;
}
if($action!="edit") {

} else {
	if(lib_access_check("wiki","edit")) {

	echo  "<form action='$RFS_SITE_URL/modules/wiki/rfswiki.php' method='post'>
			<input type=hidden name=action value=editname>
			<input type=hidden name=name value='$name'>
			<input id='nname' name=nname value=\"$name\" size=120 onblur=\"this.form.submit()\">
			</form> \n";
	
	}
    
}

echo "<hr>";

if($name=="Contents") {	
	// Add in limited number of contents displayed per page
	
} else { 
	$res = rfs_query("	select distinct name from wiki where 
						`text` like '%[$name]%' or 
						`text` like '%\@$name,%' 
						order by name asc" ); 
	$num = mysql_num_rows($res);
	if($num) {
		echo "Linked Pages ($num) >> ";
		for($i=0;$i<$num;$i++) {
			$wpage=mysql_fetch_object($res);
			if(!empty($wpage->name))
			echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=".urlencode($wpage->name).">$wpage->name</a>]";
		}
		echo "<hr>";
	}
}
//////////////////////////////////////////////////////////////////////////////
if($name=="Contents") {
    $res=rfs_query("select distinct name from wiki order by name asc");
    $num=mysql_num_rows($res);
	
    echo "<h3>RFSWiki All Pages ($num)</h3>";
    echo "<table border=0 cellspacing=0 cellpadding=0>";
    for($i=0;$i<$num;$i++)    {
		$tpage=mysql_fetch_object($res);
		$wpage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
		
		
        echo "<tr><td class=rfswiki_contenttd>";
        echo "<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=".urlencode($wpage->name).">$wpage->name</a>";
        echo "</td>";
        echo "<td class=rfswiki_contenttd> &nbsp; $wpage->author &nbsp; </td>";
		echo "<td class=rfswiki_contenttd>";
		if($wpage->revision) {
			echo "Last revision: $wpage->revision by $wpage->revised_by";
		}

		echo "</td>";
        echo "<td class=rfswiki_contenttd> &nbsp; ".rfs_time($wpage->updated)." &nbsp; </td>";
        echo "</tr>";
    }
    echo "</table>";
}
//////////////////////////////////////////////////////////////////////////////

$res=rfs_query("select * from wiki where name='$name'");
$wikipage=mysql_fetch_object($res);

if($GLOBALS['rfsw_admin_mode']=="true"){
    if($action=="createnewpage")    {
        echo "<h3>Enter the name of the page to create below</h3>";
        echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/wiki/rfswiki.php>";
        echo "<input type=hidden name=action value=editgo>";
        echo "<input name=name value=\"Page Name\">";
        echo "<input type=submit name=submit value=\"Create\">";
        echo "</form>";
    }
    if($action=="deletepage")    {
        if( ($name=="home") || ($name=="") ) {
        }
        else {
            $res=rfs_query("select * from wiki where name='$name'");
			$wikipage=mysql_fetch_object($res);
            echo "<h3>Are you sure you want to delete $wikipage->name?</h3>";
            echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/wiki/rfswiki.php>";
            echo "<input type=hidden name=action value=deletepagego>";
            echo "<input type=hidden name=name value=\"$name\">";
            echo "<input type=submit name=submit value=confirm>";
            echo "</form>";
        }
    }

    if($action=="deletepagego")    {
        rfs_query("delete from wiki where `name`='$name'");
        echo "Page ($name) deleted.";
    }

    if($action=="editgo")    {
        $time=date("Y-m-d H:i:s");
        $res=lib_mysql_query("select * from wiki where name='$name'");
		$tpage=mysql_fetch_object($res);
		$wikipage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
		$revision=$wikipage->revision+1;
		$wikiedittext=addslashes($wikiedittext);
        if(empty($data->name)) $data->name="Guest";
		rfs_query("insert into
					wiki 	(`name`,`author`,		`text`,				`updated`,`revision`,	`revised_by`,`revision_note`)
					values	('$name','$data->name','$wikiedittext',	'$time',	'$revision', '$data->name', '$revision_note');");
    }
}

$res=rfs_query("select * from wiki where name='$name'");
$tpage=mysql_fetch_object($res);	
$wikipage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
if( ($action=="viewpagebyid") || ($id) ) {
	$wikipage=lib_mysql_fetch_one_object("select * from wiki where id='$id'");
}


if($action=="edit"){
    if($GLOBALS['rfsw_admin_mode']=="true")    {
        echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/wiki/rfswiki.php>";
		echo "<input type=hidden name=action value=editgo>";
        echo "<input type=hidden name=name value=\"$name\">";
        echo "<textarea rows=30 cols=120 style=\"width: 80%;\" name=wikiedittext>";
        echo   $wikipage->text;
        echo "</textarea><br>";
		
		$lastpage=lib_mysql_fetch_one_object("select * from wiki where name='$wikipage->name' order by revision desc limit 1");
		$revision=$lastpage->revision+1;
		
		echo "<textarea style=\"width:80%\" name=revision_note>Enter revision note here. REVISION: $revision</textarea>";
        echo "<br><br><input type=submit name=submit value=update>";
        echo "</form>";
    }    else    {
        echo "You can not edit pages.";
    }
} 
else {	

    if(empty($wikipage->text)){
            if( ($name=="contents") ||
                ($name=="Contents") ){
            echo "<br>";
		}	else	{
            echo "<h2>This page is empty.</h2>";
            $name=urlencode($name);
            echo "<p>[<a class=rfswiki_link href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?action=edit&name=$name\">Edit this page</a>]</p>";
            }
    }
    else    {
		
		
		//echo "<pre>$wikipage->text </pre> ";
        
		lib_rfs_echo(wikitext(wikiimg(($wikipage->text))));
        if($hide_wiki_menu!="true")
            echo "<p>This page was created by $wikipage->author ".rfs_time($wikipage->updated)."</p>";
		
		$page="$RFS_SITE_URL/modules/wiki/rfswiki.php?name=$name";	
		if($RFS_SITE_FACEBOOK_WIKI_COMMENTS) 
			sc_facebook_comments($page);
    }
}
echo "<hr>";
lib_socials_share_bar2(lib_domain_canonical_url(),"$RFS_SITE_NAME Wiki:".$wpage);
echo "<hr>";

if($hide_wiki_menu!="true"){
	$wpage=lib_mysql_fetch_one_object("select * from wiki where name='$name' order by revision desc limit 1");
	if($wpage->revision) {
		echo "Page Revision: $wpage->revision (revised by: $wpage->revised_by) ";
	}
	
    echo "RFS Wiki ( $RFS_FULL_VERSION ) <br>";
    echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=home>main page</a>]";
    echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=contents>view all pages</a>]";
	
	
	if($wpage->revision) {
	echo "[<a class=rfswiki_link href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?action=history&name=$name\">view this page's history</a>]";
		
	}
    if( ($name=="Home") || ($name=="Contents")  || ($name=="contents") ){
        if($name=="Home")    {
            if($GLOBALS['rfsw_admin_mode']=="true")        {
				if(lib_access_check("wiki","admin"))
                echo "[<a class=rfswiki_link href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?action=edit&name=$name&id=$id\">edit this page</a>]";
            }
        }
    } else {
        $name=urlencode($name);
        if($GLOBALS['rfsw_admin_mode']=="true")    {
			if(lib_access_check("wiki","admin")) {
            echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?action=edit&name=$name&id=$id>edit this page</a>]";
            echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?action=deletepage&name=$name&id=$id>delete this page</a>]";
			}
        }
    }
    if($GLOBALS['rfsw_admin_mode']=="true")
    echo "[<a class=rfswiki_link href=$RFS_SITE_URL/modules/wiki/rfswiki.php?action=createnewpage>create new page</a>]";
}

echo "</div>";

include($rfsw_footer);

?>
