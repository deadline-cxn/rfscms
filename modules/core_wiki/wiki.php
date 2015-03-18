<?php
///////////////////////////////////////////////////////////////////////////////
// RFS Wiki (Really Frickin Simple)
// By Seth Parson http://www.SethCoder.com/
$title="Wiki ".$_REQUEST['name'];
chdir("../../");
include_once("include/lib.all.php");
if(empty($RFS_SITE_WIKI_BULLET_IMAGE)) $RFS_SITE_WIKI_BULLET_IMAGE = $RFS_SITE_URL."/modules/core_wiki/images/bullet.gif";
if(empty($RFS_SITE_WIKI_LINK_IMAGE))   $RFS_SITE_WIKI_LINK_IMAGE   = $RFS_SITE_URL."/modules/core_wiki/images/link2.png";
if(empty($RFS_SITE_PATH)) $RFS_SITE_PATH = getcwd();
if(!empty($RFS_SITE_WIKI_HEADER))  include($RFS_SITE_WIKI_HEADER);

$addon_url=lib_modules_get_url("core_wiki");

///////////////////////////////////////////////////////////////////////////////
// DO NOT MODIFY BELOW THIS LINE
///////////////////////////////////////////////////////////////////////////////

function finish_wiki_page() {
	eval(lib_rfs_get_globals());
	echo "</div>";
	include($RFS_SITE_WIKI_FOOTER);
}

function wiki_buttons() {
	eval(lib_rfs_get_globals());

	if(!lib_rfs_bool_true($RFS_SITE_HIDE_WIKI_MENU)) {

		$wpage=lib_mysql_fetch_one_object("select * from wiki where name='$name' order by revision desc limit 1");

		echo "[<a class=rfswiki_link href=$addon_url?name=home>main page</a>]";
		echo "[<a class=rfswiki_link href=$addon_url?name=contents>view all pages</a>]";


		if($wpage->revision) {
		echo "[<a class=rfswiki_link href=\"$addon_url?action=history&name=$name\">view this page's history</a>]";

		}
		if( ($name=="Home") || ($name=="Contents")  || ($name=="contents") ){
			if($name=="Home")    {
				if(lib_access_check("wiki","admin"))
					echo "[<a class=rfswiki_link href=\"$addon_url?action=edit&name=$name&id=$id\">edit this page</a>]";
			}
		} else {
			$name=urlencode($name);
			if(lib_access_check("wiki","admin")) {
				echo "[<a class=rfswiki_link href=$addon_url?action=edit&name=$name&id=$id>edit this page</a>]";			
				echo "[<a class=rfswiki_link href=$addon_url?action=deletepage&name=$name&id=$id>delete this page</a>]";
			}
		}
		
		if(lib_access_check("wiki","admin"))
			echo "[<a class=rfswiki_link href=$addon_url?action=createnewpage>create new page</a>]";
	}
}

if($give_file=="yes"){
    if(lib_access_check("wiki","uploadfile")) {
        $file=$_FILES[$fname]['name'];
        $uploadFile="$short_name";
        move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile);
        echo "Uploaded $uploadFile";
    }
}

function wiki_action_history() {
	eval(lib_rfs_get_globals());
	echo "<hr>";
	echo "$name history: <br>";
	echo "<hr>";
	$r=lib_mysql_query("select * from wiki where name='$name'");
	for($i=0;$i<$r->num_rows;$i++){
		echo "<div class=\"forum_box\">";
		$wpage=$r->fetch_object();
		echo "<a href=\"$addon_url?action=viewpagebyid&id=$wpage->id&name=$wpage->name\">$wpage->name</a> ";
		echo "REVISION: $wpage->revision ";
		if(empty($wpage->revised_by)) $wpage->revised_by=$wpage->author;
		echo "by $wpage->revised_by <br>";
		echo "REVISION NOTE: $wpage->revision_note";
		echo "</div>";
	}
	finish_wiki_page();
}

function wiki_action_createnewpage() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("wiki","admin")){
        echo "<h3>Enter the name of the page to create below</h3>";
        echo "<form enctype=application/x-www-form-URLencoded action=$addon_url>";
        echo "<input type=hidden name=action value=editgo>";
        echo "<input name=name value=\"Page Name\">";
        echo "<input type=submit name=submit value=\"Create\">";
        echo "</form>";	
	}
	finish_wiki_page();
}

function wiki_action_deletepage() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("wiki","delete")){
		if( ($name=="home") || ($name=="") ) {
		}
		else {
			$res=lib_mysql_query("select * from wiki where name='$name'");
			$wikipage=$res->fetch_object();
			echo "<h3>Are you sure you want to delete $wikipage->name?</h3>";
			echo "<form enctype=application/x-www-form-URLencoded action=$addon_url>";
			echo "<input type=hidden name=action value=deletepagego>";
			echo "<input type=hidden name=name value=\"$name\">";
			echo "<input type=submit name=submit value=confirm>";
			echo "</form>";
		}
	}
	finish_wiki_page();
}

function wiki_action_deletepagego() {
	if(lib_access_check("wiki","delete")){
		lib_mysql_query("delete from wiki where `name`='$name'");
		echo "Page ($name) deleted.";
	}
}

function wiki_action_editgo() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("wiki","edit")){
		$time=date("Y-m-d H:i:s");
		$res=lib_mysql_query("select * from wiki where name='$name'");
		$tpage=$res->fetch_object();
		$wikipage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
		$revision=$wikipage->revision+1;
		$wikiedittext=addslashes($wikiedittext);
		if(empty($data->name)) $data->name="Guest";
		lib_mysql_query("insert into
					wiki 	(`name`,`author`,		`text`,				`updated`,`revision`,	`revised_by`,`revision_note`)
					values	('$name','$data->name','$wikiedittext',	'$time',	'$revision', '$data->name', '$revision_note');");
	}
	wiki_action_();
}


function wiki_action_editname() {
	eval(lib_rfs_get_globals());
    if(lib_access_check("wiki","edit")) {
		lib_mysql_query("update wiki set name='$nname' where name='$name'");
		$res = lib_mysql_query(" 	select * from wiki where `text` like '%[$name]%' or `text` like '%,$name]%' order by name asc" ); 
		$npg=$res->num_rows;
		for($ni=0;$ni<$npg;$ni++) {
			$pg=$res->fetch_object();
		$pg->text=str_ireplace("[$name]","[$nname]",$pg->text);
		$pg->text=str_ireplace("\@$name,","\@$nname,",$pg->text);
			lib_mysql_query("update wiki set text='$pg->text' where name='$pg->name'");
		}
		echo "Name is changed, and links have been updated throughout $npg wiki pages.<br>";
		wiki_action_();
	}
}


function wiki_action_edit(){
	eval(lib_rfs_get_globals());
    if(lib_access_check("wiki","edit")) {

		echo "<div class=\"wikitext\">";
		echo "<hr>";		
		if(empty($name)) $name="home";
		$res=lib_mysql_query("select * from wiki where name='$name'");
		$tpage=$res->fetch_object();
		$wikipage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
		if( ($action=="viewpagebyid") || ($id) ) {
			$wikipage=lib_mysql_fetch_one_object("select * from wiki where id='$id'");
		}		
		
		echo  "<form action='$addon_url' method='post'>
			<input type=hidden name=action value=editname>
			<input type=hidden name=name value='$name'>
			<input id='nname' name=nname value=\"$name\" size=120 onblur=\"this.form.submit()\">
			</form> \n";
			
        echo "<form enctype=application/x-www-form-URLencoded method=post action=$addon_url>";
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

function wiki_action_viewpagebyid() {
	wiki_action_();
}

function wiki_action_() {
	eval(lib_rfs_get_globals());
	echo "<div class=\"wikitext\">";

	if(lib_rfs_bool_true($RFS_SITE_WIKI_TOP_BUTTONS)) wiki_buttons();

       if(empty($name)) $name="home";

	$res=lib_mysql_query("select * from wiki where name='$name'");
	$wikipage=$res->fetch_object();
	$name=ucwords($name);

	lib_rfs_echo("<h1>$name</h1>");
	
	
	$res=lib_mysql_query("select * from wiki where name='$name'");
	$tpage=$res->fetch_object();
	$wikipage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
	if( ($action=="viewpagebyid") || ($id) ) {
		$wikipage=lib_mysql_fetch_one_object("select * from wiki where id='$id'");
	}
	
	if($name=="Contents") {	
		// TODO: Add in limited number of contents displayed per page
        // ie;  [<<]  [<] [FIRST] [2] [3] [4] [5] [LAST] [>] [>>]	
	}
    else {
        
            if(lib_rfs_bool_true($RFS_SITE_WIKI_SHOW_LINKED_PAGES)) {
    		  $res=lib_mysql_query( " select distinct name from wiki where `text` like '%[$name]%' or `text` like '%[\@$name,%' order by name asc" );
                $num=$res->num_rows;
                if($num) echo "Linked Pages ($num) >> ";		 
                while($wpage=$res->fetch_object()) {
        		if(!empty($wpage->name))
    				    echo "[<a class=rfswiki_link href=$addon_url?name=".urlencode($wpage->name).">$wpage->name</a>]";
    		  }
    		  echo "<hr>";
          }
			
	}
	

	if($name=="Contents") {
		$res=lib_mysql_query("select distinct `name` from `wiki` order by `name` asc");

		echo "<h3>RFSWiki All Pages ($num)</h3>";
		echo "<table border=0 cellspacing=0 cellpadding=0>";
		if($res)
		while($tpage=$res->fetch_object()) {
			$wpage=lib_mysql_fetch_one_object("select * from wiki where name='$tpage->name' order by revision desc limit 1");
			echo "<tr><td class=rfswiki_contenttd>";
			echo "<a class=rfswiki_link href=\"$addon_url?name=".urlencode($wpage->name)."\">$wpage->name</a>";
			echo "</td>";
			echo "<td class=rfswiki_contenttd> &nbsp; $wpage->author &nbsp; </td>";
			echo "<td class=rfswiki_contenttd>";
			if($wpage->revision) {
				echo "Last revision: $wpage->revision by $wpage->revised_by";
			}

			echo "</td>";
			echo "<td class=rfswiki_contenttd> &nbsp; ".lib_string_current_time($wpage->updated)." &nbsp; </td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
    if(empty($wikipage->text)){
            if( ($name=="contents") ||
                ($name=="Contents") ){
            echo "<br>";
		}	else	{
            echo "<h2>This page is empty.</h2>";
            $name=urlencode($name);
            echo "<p>[<a class=rfswiki_link href=\"$addon_url?action=edit&name=$name\">Edit this page</a>]</p>";
            }
    }
    else    {
	
		lib_rfs_echo(wikitext(wikiimg(($wikipage->text))));
        if(!lib_rfs_bool_true($RFS_SITE_HIDE_WIKI_MENU)) {
			echo "<hr>";
			$tres=lib_mysql_query("select * from wiki where name='$name' and revision='0'");
			$twik=$tres->fetch_object();
            echo "<p>This page was created by $wikipage->author ".lib_string_current_time($twik->updated); 
			if($wikipage->revision) {
				echo " (revision: $wikipage->revision by: $wikipage->revised_by ".lib_string_current_time($wikipage->updated).")</p>";
			}
		}
		
		$page="$addon_url?name=$name";	
		if(lib_rfs_bool_true($RFS_SITE_WIKI_FACEBOOK_COMMENTS))
			lib_social_facebook_comments($page);

		if(lib_rfs_bool_true($RFS_SITE_WIKI_SOCIALS)) {
			$u=lib_domain_canonical_url();
			$p="$RFS_SITE_NAME Wiki:".$name;
			echo "<hr>";
			lib_social_share_bar2($u,$p);
		}
	}
	
	wiki_buttons();
	
	finish_wiki_page();
}

?>
