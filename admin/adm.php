<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
$title="Administration";
/////////////////////////////////////////////////////////////////////////////////////////
include("lib.adm.php");
chdir( "../" );

if(stristr($_REQUEST['action'],"ajx")) {
	include("include/lib.all.php");
	// sc_do_action();
	exit();
}
else {
		include( "lilheader.php" );
		//sc_do_action();
}

/////////////////////////////////////////////////////////////////////////////////////////
// ACCESS CHECK
if(!sc_access_check("admin","access")) {
	echo smiles( "<table border=0 width=300><tr><td class=warning><center>^X<br>You can not use admin</td></tr></table>\n" );
	sc_log( "*****> $data->name tried to access the admin area!" );
	include("footer.php");
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM CHECK FOR UPDATES
function adm_action_update() { eval(scg());
	echo "<pre>";
	sc_flush_buffers();
	system("git stash save --keep-index");
	sc_flush_buffers();
	system("git pull https://github.com/sethcoder/rfscms.git");
	sc_flush_buffers();
	echo "</pre>";
	include("footer.php");
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_AUTH FUNCTIONS
function adm_action_auth_config() { eval(scg());

	sc_database_add("rfsauth","name","text","NOT NULL");
	sc_database_add("rfsauth","enabled","text","NOT NULL");
	sc_database_add("rfsauth","value","text","NOT NULL");
	sc_database_add("rfsauth","value2","text","NOT NULL");
	
	$id =	sc_database_data_add("rfsauth","name","EBSR",0);
			sc_database_data_add("rfsauth","enabled","true",$id);
			sc_database_data_add("rfsauth","value","",$id);
			
	$id =	sc_database_data_add("rfsauth","name","FACEBOOK",0);
			sc_database_data_add("rfsauth","enabled","false",$id);
			sc_database_data_add("rfsauth","value","",$id);
			sc_database_data_add("rfsauth","value2","",$id);
			
	$id =	sc_database_data_add("rfsauth","name","OPENID",0);
			sc_database_data_add("rfsauth","enabled","false",$id);
			sc_database_data_add("rfsauth","value","",$id);
	

	echo "<h1>Authentication Configuration</h1>";
	echo "<hr>";
	
	echo "<div> EBSR (Email-based Self-registration)";
	sc_ajax(	"Enabled",		"rfsauth",	"name", "EBSR", "enabled", 		1, "checkbox", "admin", "access", "");
	sc_ajax(	"EBSR Value,180",	"rfsauth", "name", "EBSR", "value", 		60, "", 		"admin", "access", "");						
	echo "</div>";
	echo "<hr>";

	echo "<div> Facebook";
	sc_ajax(	"Enabled",				"rfsauth",	"name", "FACEBOOK", "enabled", 		1, "checkbox", "admin", "access", "");
	sc_ajax(	"Facebook  APP  ID,180",	"rfsauth", "name", "FACEBOOK", "value", 		60, "", 		"admin", "access", "");						
	sc_ajax(	"Facebook SECRET ID,180","rfsauth", "name", "FACEBOOK", "value2", 		60, "", 		"admin", "access", "");						
	echo "</div>";
	echo "<hr>";
	
	echo "<div> OpenID";
	sc_ajax(	"Enabled",		"rfsauth",	"name", "OPENID", "enabled", 		1, "checkbox", "admin", "access", "");
	sc_ajax(	"OpenID ID,180",	"rfsauth", "name", "OPENID", "value", 		60, "", 		"admin", "access", "");						
	echo "</div>";
	
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ARRANGE FUNCTIONS
function sc_admin_module( $loc ) { eval( scg() );
    $location=$loc;
	$r=sc_query( "select * from arrangement where location='$location' order by sequence " );
	if($r){
		echo "<center><h2>Arrange location $location";
		if( $location=="left" ) echo " (left panel)";
		echo "</h2></center>";
		$n=mysql_num_rows($r);
        if(!$n) echo " ( NOTHING IN THIS AREA! ) <BR> ";
        else
		for( $i=0; $i<$n; $i++ ) {
			$ar=mysql_fetch_object( $r );
			echo "<table border=0 cellspacing=0><tr><td>";
            echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_delete&location=$location&arid=$ar->id'>";
			echo "<img src='$RFS_SITE_URL/images/icons/circle-delete.png' border='0'>";
            echo "</a>";

			if( $ar->sequence > 1 ) {
				echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_move_up&arid=$ar->id'>";				
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-up.png width=32 height=32 border=0> ";
				echo "</a>";
			}
			if( $ar->sequence < $n ) {
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-down.png width=32 height=32 border=0> ";
				
			}
			if( $location!="left" ) {
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-left.png width=32 height=32 border=0> ";
				
			}
			if( $location!="right" ) {
				
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-right.png width=32 height=32 border=0> ";				
			}
			echo "	</td><td>";
			echo ucwords( "Module: $ar->mini show " );
			echo "</td>	<form action='$RFS_SITE_URL/admin/adm.php' method='post'>	<td>
			<input type=hidden name=action value=f_module_chg_num>
			<input type=hidden name=id value='$ar->id'>
			<input name=num size=1 value=$ar->num  onblur='this.form.submit()'  >
			</td>	</form>	<td> results</td>	</tr></table>";
		}
		echo "<p>&nbsp;</p>";
	}
	echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>";
	echo "<input type=hidden name=action value=f_module_add>";
	echo "<input type=hidden name=location value=$location>";
	echo "<select name=module onchange='this.form.submit();'>";
	echo "<option>Add module to this area";
	$arr=get_defined_functions();
	foreach( $arr['user'] as $k=>$v ) {
		if( stristr( $v,"sc_module_" ) ) {
			$v=str_replace( "sc_module_","",$v );
			echo "<option name='$v' value='$v'>";
			echo ucwords( str_replace( "_"," ",$v ) );
		}
	}
	echo "</select>";
	echo "</form>";
}
function adm_action_f_arrange_move_up() { eval(scg());
	
	$ar=mfo1("select * from arrangement where `id`='$arid'");	
	//echo $ar->mini."<br>";
	//echo $ar->location."<br>";
	//echo $ar->num."<br>";
	//echo $ar->sequence."<br>";
	
	$arrange=array();
	$r=sc_query("select * from arrangement where `location`='$ar->location'");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$tar=mysql_fetch_object($r);
		$arrange[$tar->id]=$tar->sequence;
	}
	
	$ar->sequence--;
	foreach($arrange as $k => $v) {
		if($v==$ar->sequence) $arrange[$k]=$v+1;
	}
	$arrange[$ar->id]=$ar->sequence;
	foreach($arrange as $k => $v) {
		//$x=mfo1("select * from arrangement where `id`='$k'");
		sc_query("update arrangement set `sequence`='".$arrange[$k]."' where `id`='$k'");
	}
	
	
	
	// print_r($arrange);
	adm_action_arrange();
	
}
function adm_action_f_arrange_delete_go() { eval(scg());
    sc_query("delete from arrangement where `id`='$id'");
    adm_action_arrange();
}
function adm_action_f_arrange_delete() { eval(scg());
    $ar=mfo1("select * from arrangement where `location` = '$location' and `id`= '$arid' ");
    echo "Delete arrangement ($location: $ar->mini)<br>";
    sc_confirmform( "Delete $ar->mini from $location?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_arrange_delete_go".$RFS_SITE_DELIMITER."id=$ar->id" );
    adm_action_arrange();
}
function adm_action_f_module_add() { eval( scg() );
	echo ".. $module... $location";
	$r=sc_query("select max(sequence) as seq from arrangement where location = '$location'");
	$ars=mysql_fetch_object($r);
	$nseq=$ars->seq+1;
	echo "$ars->seq $nseq  <br>";
	sc_query( "insert into arrangement  (`mini`,`location`,`num`,`sequence`)
	          values('$module','$location','5','$nseq');" );
	adm_action_arrange();
}
function adm_action_f_module_chg_num() { 	eval( scg() );
	sc_query( "update arrangement set num='$num' where id='$id'" );
	adm_action_arrange();
}
function adm_action_arrange() { eval( scg() );
    $location="";
	sc_query(" CREATE TABLE IF NOT EXISTS `arrangement` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`location` text NOT NULL,
				`mini` text NOT NULL,
				`num` int(11) NOT NULL,
				`sequence` int(11) NOT NULL,
				PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ; ");

	echo "<table border=0><tr>"; // TOP START
	echo "<td valign=top>";// class=lefttd>";	
	sc_admin_module("left");
	echo "</td><td valign=top>"; // MIDDLE START
    sc_admin_module("middle");
    echo "</td><td valign=top>"; // RIGHT SIDE START
    sc_admin_module("right");
    echo "</td> </tr> </table>"; // TOP END
    echo "<table border=0><tr><td>"; // BOTTOM START
    sc_admin_module("bottom");
    echo "</td></tr></table>"; // BOTTOM END
	include( "footer.php" );
	exit();
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM ACCESS GROUPS FUNCTIONS
function adm_action_f_access_group_add() { eval(scg());
	echo " Adding new access group named [$axnm] <br>";
	sc_query(" insert into access (`name`) VALUES ('$axnm'); ");
	adm_action_f_access_group_edit();
}
function adm_action_f_access_group_edit_go() { eval(scg());
	sc_query("delete from `access` where name='$axnm'");
	$r=sc_query("select * from access_methods");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$am=mysql_fetch_object($r);
		if($_POST["$am->page"."_$am->action"]=="on") {
			sc_query("insert into access (`name`,`page`,`action`) 
			VALUES('$axnm','$am->page','$am->action')");
		}
	}
	adm_action_access_groups();
}
function adm_action_f_access_group_edit() { eval(scg()); 
	echo "<h1>Edit Access Group</h1>";
	echo "<h2>$axnm</h2>";
	echo "<div class=\"forum_box\">";
	echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"f_access_group_edit_go\">";
	echo "<input type=\"hidden\" name=\"axnm\" value=\"$axnm\">";
	
	$r=sc_query("select * from access_methods order by page,action");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$am=mysql_fetch_object($r);	
		$checked="";
		$rw=mfo1("select * from access where name='$axnm' and page='$am->page' and action='$am->action'");
		if($rw->name==$axnm) { $checked="checked";}
		echo "<div style=\"float: left; width: 200px;\">";
		echo "<input name=\"$am->page"."_$am->action\" type=checkbox $checked>";
		echo " $am->page -> $am->action";
		echo "</div>";
	}	
	echo "<div style=\"clear: left;\"></div>";
	echo "</div>";
	
	echo "<input type=\"submit\" value=\"Update\">";
	
	echo "</form>";
	include( "footer.php" );
	exit();
}
function adm_action_f_access_group_add_user() { eval(scg());
	$usr=sc_getuserdata($name);
	$usr->access_groups.=",$axnm";	
	sc_query("update users set access_groups ='$usr->access_groups' where id='$usr->id'");
	adm_action_access_groups();
}
function adm_action_f_access_group_del_user() { eval(scg());
	echo $user;
	echo "<br>";
	$usr=sc_getuserdata($user);
	$agx=explode(",",$usr->access_groups);
	for($i=0;$i<count($agx);$i++){
		if($agx[$i]!=$axnm) {
			$outag=$outag.$agx[$i].",";
		}
	}
	$outag=rtrim($outag,",");
	sc_query("update users set access_groups='$outag' where name='$user'");
	adm_action_access_groups();	
}
function adm_action_f_access_group_delete() { eval(scg());
	sc_confirmform( "Delete $axnm?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_access_group_delete_go".$RFS_SITE_DELIMITER.
					  "axnm=$axnm" );
	adm_action_access_groups();
}
function adm_action_f_access_group_delete_go() { eval(scg());
	echo "DELETE $axnm access group... <BR>";
	sc_query("delete from `access` where name='$axnm'");
	adm_action_access_groups();
}
function adm_action_access_groups() { eval(scg());
	echo "<h1>Modify Access Groups</h1>";
	echo "<hr>";
	echo "<h2>Create a new access group</h2>";
	sc_div("ADD ACCESS GROUP FORM START");
	echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"f_access_group_add\">\n";
	echo "<input name=\"axnm\">\n";
	echo "<input type=\"submit\" value=\"Add\">\n";
	echo "</form>\n";
	sc_div("ADD ACCESS GROUP FORM END");
	
	$r=sc_query("select distinct name from access");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		
		echo "<div class=\"forum_box\" style=\"float:left; width:300px;\">";
		$a=mysql_fetch_object($r);
		echo "<h2>$a->name</h2>";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_delete&axnm=$a->name\">delete</a>] ";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_edit&axnm=$a->name\">edit</a>]<br>";
		
		echo "<p>Members: </p>";
		echo "<p>";
		
		$usrs=sc_query("select * from `users`");
		for($j=0;$j<mysql_num_rows($usrs);$j++) {
			$usr=mysql_fetch_object($usrs);
			$agrps=explode(",",$usr->access_groups);
			for($k=0;$k<count($agrps);$k++) {
				if($a->name==$agrps[$k]) {
					
					echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_del_user&axnm=$a->name&user=$usr->name\">remove</a>] ";
					
					echo " $usr->name <br>";
				}
			}
		}
		
		
		sc_optionizer(	"$RFS_SITE_URL/admin/adm.php",
							"action=f_access_group_add_user".$RFS_SITE_DELIMITER.
							"axnm=$a->name".$RFS_SITE_DELIMITER.
							"omit=access_groups:like '%$a->name%',name:anonymous",
							"users",
							"name",
							0,
							"Add a user to this group",
							1 );
		echo "</p>";
		echo "</div>";
	}
	
	
	
	include( "footer.php" );
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_PHPMYADMIN
function adm_action_phpmyadmin_out() { eval(scg()); sc_gotopage("$RFS_SITE_URL/3rdparty/phpmyadmin/");}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_FORM BUILDER FUNCTIONS
/*
function adm_action_form_builder() { eval(scg());
	echo"<p>Form Builder</p>";
	include( "footer.php" );
	exit();
}
*/
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_NEW PAGE FUNCTIONS
function adm_action_new_page() { eval(scg());
	echo"<p>Create a new page.</p>";
	sc_bqf( "action=new_page_go".$RFS_SITE_DELIMITER."SHOW_TEXT_name=name.php","Create new page");
	include( "footer.php" );
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function adm_action_new_page_go() { eval(scg());
	if(!file_exists($_GLOBALS['name'])){
	copy("_template.php",$_GLOBALS['name']);
	}
	echo "<p> New file ".$_GLOBALS['name']." created. </p>";
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_EMAIL
function adm_action_email() { eval(scg());
	echo"<p>Send an email</p>";
	sc_bqf( 	"action=email_go".$RFS_SITE_DELIMITER.
				"SHOW_TEXT_address=address".$RFS_SITE_DELIMITER.
				"SHOW_TEXT_subject=subject".$RFS_SITE_DELIMITER.
				"SHOW_CODEAREA_300#600#message=message".$RFS_SITE_DELIMITER,
				"Email");
	include( "footer.php" );
	exit();	
}
function adm_action_email_go() { eval(scg());
    echo "Sending message:<br>";
    echo "TO:$address<br>SUBJECT:$subject<br>MESSAGE:<br>$message<br>";
    mailgo($address,$message,$subject);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_DATABASE
/////////////////////////////////////////////////////////////////////////////////////////

function adm_db_query( $x ) { eval( scg() );
	$y=urlencode($x);
	echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_rm_db_query&query=$y\" target=_top>Delete</a>]";	
	echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=db_query&query=$y\" target=_top>$x</a><br>";
}
function adm_action_f_rm_db_query() { eval(scg());
	// $query=addslashes($query);
	// $query=urldecode($query);
	echo "DING DING [$query]<BR>";	
	$q="delete from `db_queries` where `query` = \"$query\";";
	echo "DING DING [$q] <br>";
	sc_query($q);
	$query="";
	$_GLOBALS['query']="";
	$_POST['query']="";
	$_GET['query']="";
   echo "<h3>Select a previously entered query</h3>";
   echo "<iframe id=\"QU\" width=600 height=400 class='iframez' frameborder=0
			src=\"$RFS_SITE_URL/admin/adm.php?action=sc_ajax_callback_query_list\"
			style=\"float:left;\"></iframe>";
	echo "<div style=\"float:left;\">";
	echo "<h3>Enter a new query</h3>";
	sc_db_query_form( "$RFS_SITE_URL/admin/adm.php","db_query","$query" );
	echo "</div><div style=\"clear:both;\">";
	finishadminpage();
}
function adm_action_db_query() { eval(scg());

	$r=sc_query("select * from db_queries");
	
	for($x=0;$x<mysql_num_rows($r);$x++) {
		$q=mysql_fetch_object($r);
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		sc_query("update db_queries set query= '$q->query' where `id`='$q->id'");
	}

   echo "<h3>Select a previously entered query</h3>";
   echo "<iframe id=\"QU\" width=600 height=400 class='iframez' frameborder=0
			src=\"$RFS_SITE_URL/admin/adm.php?action=sc_ajax_callback_query_list\"
			style=\"float:left;\"></iframe>";
	echo "<div style=\"float:left;\">";
	echo "<h3>Enter a new query</h3>";
	sc_db_query_form( "$RFS_SITE_URL/admin/adm.php","db_query","$query" );
	echo "</div><div style=\"clear:both;\">";
	if( !empty( $query ) ) {	
		// echo " DING [$query] <BR>";	
		sc_query( "insert into `db_queries` (`id`, `query`) VALUES ('',\"$query\" ) " );

		echo $query;
		echo "<table cellspacing=0 cellpadding=0 border=0><tr><td class=contenttd>";
		sc_db_query( $query, "true" );
		echo "</td></tr></table>";
	}
	
    finishadminpage();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_EVAL FUNCTIONS
function eval_callback( $txt ) {
	if( stristr( $GLOBALS['eval'],"phpinfo()" ) ) {
		$txt=str_replace( "border: 1px solid #000000;","border: 0px solid #000000;",$txt );
		$txt=str_replace( ".e {",".e {border: 1px solid #000000; ",$txt );
		$txt=str_replace( ".h {",".h {border: 1px solid #000000; ",$txt );
		$txt=str_replace( ".v {",".v {border: 1px solid #000000; ",$txt );
		$txt=str_replace( "img {",".imgg {",$txt );
		$txt=str_replace( "<img","<img class=imgg",$txt );
	}
	return $txt;
}
function adm_action_eval_form_go() {
	eval( scg() );
	$eval=stripslashes( $eval );
	ob_start( "eval_callback" );
	eval( "$eval" );
	ob_end_flush();
	finishadminpage();
}
function adm_action_eval_form() {
	eval( scg() );
	echo "<h3>Enter PHP code to eval:</h3><br>";
	sc_bf( sc_phpself(),
	       "action=eval_form_go".$RFS_SITE_DELIMITER.
	       "id=$id".$RFS_SITE_DELIMITER.
	       "SHOW_TEXTAREA_16#70#eval=enter code here",
	       "","","","","","",50,"Eval Code" );
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_THEMES
function adm_action_f_theme_edit_css() { eval(scg());
	if(!empty($update)) {
		echo "<h1>UPDATE:</h1>";
		echo " ($outfile)<br>";
		$cssvalue=stripslashes($cssvalue);
		$newvalue=stripslashes($newvalue);
		$newvalue=trim($newvalue,";");
		echo " (CHANGE $update{ $sub: $cssvalue; } TO $update{ $sub: $newvalue; ) <br>";
		
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		$foundbase=0;
		while($ln=fgets($fp,256)) {		
			$chkr=explode("{",$ln);
			$chkr[0]=trim($chkr[0]);
			
			if($chkr[0]==$update) $foundbase=1;
			if($foundbase) {
				if(stristr($ln,"{")) $foundbase=2;
			}
			
			if($foundbase==2) {
				$chks=explode(":",$ln);
				$chks[0]=trim($chks[0]);
				
				if($sub==$chks[0]) {
					echo "FOUND $update { $sub ... } UPDATING<br>";
					if(stristr($sub,"color")) 
						if(!stristr($newvalue,"#"))
							$newvalue="#$newvalue";
					fputs($fo,"$sub: $newvalue;\n");
					continue;
				}
				if(stristr($ln,"}")) $foundbase=0;
			}
			fputs($fo,$ln);		
		}		
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
		
	}

	if(!empty($delete)) {
		echo "<h1>DELETE:</h1>";
		echo " --- delete[$delete]<br>";
		echo " ($outfile)<br>";
		echo " ($delete{ $sub: $cssvalue; }) <br>";
		
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		$foundbase=0;
		while($ln=fgets($fp,256)) {		
			if(stristr($ln,$delete)) $foundbase=1;
			if($foundbase) {
				if(stristr($ln,"{")) $foundbase=2;
			}
			if($foundbase==2) {
				if(stristr($ln,$sub)) {
					echo "FOUND $delete { $sub ... } REMOVING<br>";
					continue;
				}
				if(stristr($ln,"}")) $foundbase=0;
			}
			fputs($fo,$ln);		
		}		
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
	}
	
	if(!empty($add)) {
		echo "<h1>ADD:</h1>";
		echo " --- add[$addvar=$varvalue]<br>";
		/*
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		while($ln=fgets($fp,256)) {
			fputs($fo,$ln);		
		}
		// fputs($fo,"$addvar=\"$varvalue\";\n");
		
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
		*/
	}
	adm_action_f_theme_edit();
}
function adm_action_f_theme_edit_php() { eval(scg());

	If(!empty($update)) {
		echo "<h1>UPDATE:</h1>";
		echo " --- update[$update][$phpvalue] to [$newvalue]<br>";
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		while($ln=fgets($fp,256)) {
			$ln=trim($ln);
			$chk=explode("=",$ln);
			$chk[0]=trim($chk[0]," ");			
			if($chk[0]==$update) {
				$ln="$update=\"$newvalue\";\n";				
			}
			fputs($fo,"$ln\n");
		}
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
	}




	if(!empty($delete)) {
		echo "<h1>DELETE:</h1>";
		echo " --- delete[$delete]<br>";
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		while($ln=fgets($fp,256)) {
			$chk=explode("=",$ln);
			$chk[0]=trim($chk[0]," ");
			if($chk[0]!=$delete) {
				fputs($fo,$ln);
			}
		}
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
	}
	if(!empty($add)) {
		echo "<h1>ADD:</h1>";
		echo " --- add[$addvar=$varvalue]<br>";
		system("$RFS_SITE_SUDO_CMD touch $outfile.out");
		system("$RFS_SITE_SUDO_CMD chmod 777 $outfile.out");
		$fo=fopen("$outfile.out",wt);
		$fp=fopen($outfile,"rt");
		while($ln=fgets($fp,256)) {
			if(substr($ln,0,2)!="?>") fputs($fo,$ln);		
		}
		fputs($fo,"$addvar=\"$varvalue\";\n");
		fputs($fo,"?>");
		fclose($fo);
		fclose($fp);
		system("$RFS_SITE_SUDO_CMD mv $outfile $outfile.bak.".time());
		system("$RFS_SITE_SUDO_CMD mv $outfile.out $outfile");
	}
	adm_action_f_theme_edit();
}
function adm_action_f_theme_edit_delete_go() { eval(scg());
	$file="$RFS_SITE_PATH/themes/$thm/$dfile";
	
	echo " DELETING $file...<br>";
	
	system("$RFS_SITE_SUDO_CMD rm $file");
	adm_action_f_theme_edit();
}
function adm_action_f_theme_edit_delete() { eval(scg());
	$file="$RFS_SITE_PATH/themes/$thm/$dfile";
	sc_confirmform(	"Delete $file<br><br><img src=\"$RFS_SITE_URL/themes/$thm/$dfile\" width=500> ",
						"$RFS_SITE_URL/admin/adm.php",
						"action=f_theme_edit_delete_go".$RFS_SITE_DELIMITER.
						"dfile=$dfile".$RFS_SITE_DELIMITER.
						"thm=$thm"
						
						);
}
function adm_action_f_ajx_theme_edit_save_t_php() { eval(scg());
	$thm=$_POST['thm'];
	$taval=$_POST['taval'];
	$taval=urldecode($taval);
	$taval=stripslashes($taval);
	$file="$RFS_SITE_PATH/themes/$thm/t.php";
	system("$RFS_SITE_SUDO_CMD mv $file $file.bak.".time());
	system("$RFS_SITE_SUDO_CMD touch $file");
	system("$RFS_SITE_SUDO_CMD chmod 777 $file");
	if(!file_put_contents($file,$taval))
		sc_info("ERROR SAVING FILE, CHECK PERMISSIONS","WHITE","RED");
	else 
		sc_info("FILE SAVED","WHITE","GREEN");
}
function adm_action_f_theme_edit_t_php() { eval(scg()); 
	echo "<h3> Editing theme [$thm] </h3>";	
	sc_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_edit&thm=$thm","Cancel");

	echo '<div id="file_status"></div>
		<script>
		function save_t_php(ta,taval) {
				var http=new XMLHttpRequest();
				var url = "'.$RFS_SITE_URL.'/admin/adm.php";
				var params = "action=f_ajx_theme_edit_save_t_php&thm='.$thm.'&taval="+encodeURIComponent(taval);
				document.getElementById("file_status").innerHTML="SAVING FILE....";
				http.open("POST", url, true);
				http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				http.setRequestHeader("Content-length", params.length);
				http.setRequestHeader("Connection", "close");
				http.onreadystatechange = function() {
						if(http.readyState == 4 && http.status == 200) {
							var outward=url+"?action=f_theme_edit&thm='.$thm.'";
							document.getElementById("file_status").innerHTML=http.responseText+"<a href="+outward+">Continue</a>";
					}
				}
				http.send(params);
		}
		editAreaLoader.init({
		id: "codecode_t_php"
		,start_highlight: true
		,font_size: "8"
		,font_family: "verdana, monospace"
		,allow_resize: "n"
		,allow_toggle: false
		,language: "en"
		,syntax: "php"
		,toolbar: "save,select_font"
		,load_callback: "my_load"
		,save_callback: "save_t_php"
		,plugins: "charmap" 
		,charmap_default: "arrows" });
		</script> ';
		
	echo "	<textarea id=\"codecode_t_php\"
			style=\"height: 700px; width: 100%;\"
			name=\"codecode_t_php\">";
	$fc=file_get_contents("$RFS_SITE_PATH/themes/$thm/t.php");
	
	$fc=stripslashes(str_replace("<","&lt;",$fc));
	
	echo $fc;
	
	echo "</textarea>";
	
	
	include("footer.php");
	exit();
}
function adm_action_f_ajx_theme_edit_save_t_css() { eval(scg());
	$thm=$_POST['thm'];
	$taval=$_POST['taval'];
	$taval=urldecode($taval);
	$taval=stripslashes($taval);
	$file="$RFS_SITE_PATH/themes/$thm/t.css";
	system("mv $file $file.bak.".time());
	system("touch $file");
	system("chmod 775 $file");
	if(!file_put_contents($file,$taval))
		sc_info("ERROR SAVING FILE, CHECK PERMISSIONS","WHITE","RED");
	else 
		sc_info("FILE SAVED","WHITE","GREEN");
}
function adm_action_f_theme_edit_t_css() { eval(scg()); 


	echo "<h3> Editing theme [$thm] </h3>";	
	sc_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_edit&thm=$thm","Cancel");

	echo '	<div id="file_status"></div> <script>
											
		function save_t_css(ta,taval) {
				var http=new XMLHttpRequest();
				var url = "'.$RFS_SITE_URL.'/admin/adm.php";
				var params = "action=f_ajx_theme_edit_save_t_css&thm='.$thm.'&taval="+encodeURIComponent(taval);
				document.getElementById("file_status").innerHTML="SAVING FILE....";
				http.open("POST", url, true);
				http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				http.setRequestHeader("Content-length", params.length);
				http.setRequestHeader("Connection", "close");
				http.onreadystatechange = function() {
						if(http.readyState == 4 && http.status == 200) {
							var outward=url+"?action=f_theme_edit&thm='.$thm.'";
							document.getElementById("file_status").innerHTML=http.responseText+"<a href="+outward+">Continue</a>";
					}
				}
				http.send(params);
		}
		editAreaLoader.init({
		id: "codecode_t_css"
		,start_highlight: true
		,font_size: "8"
		,font_family: "verdana, monospace"
		,allow_resize: "n"
		,allow_toggle: false
		,language: "en"
		,syntax: "css"
		,toolbar: "save,select_font"
		,load_callback: "my_load"
		,save_callback: "save_t_css"
		,plugins: "charmap" 
		,charmap_default: "arrows" });
		</script> ';
		
	echo "<textarea id=\"codecode_t_css\" style=\"height: 700px; width: 100% ;\" name=\"codecode_t_css\">";
	$fc=file_get_contents("$RFS_SITE_PATH/themes/$thm/t.css");
	$fc=stripslashes(str_replace("<","&lt;",$fc))."</textarea>";
	echo $fc;
	include("footer.php");
	exit();
}
function adm_action_f_theme_clone_go() { eval(scg());
	$new_name=strtolower($new_name);
	$new_name=str_replace(" ","_",$new_name);
	$new_name=str_replace("'","_",$new_name);
	$new_name=str_replace("\"","_",$new_name);
	$new_name=str_replace(":","_",$new_name);
	$new_name=str_replace("<","_",$new_name);
	$new_name=str_replace(">","_",$new_name);
	$new_name=str_replace("#","_",$new_name);
	$new_name=str_replace("$","_",$new_name);
	$new_name=str_replace("\\","_",$new_name);
	$new_name=str_replace("/","_",$new_name);	
	echo "Cloning $thm to $new_name<br>";
	system("mkdir $RFS_SITE_PATH/themes/$new_name");
	system("cp $RFS_SITE_PATH/themes/$thm/* $RFS_SITE_PATH/themes/$new_name");
	adm_action_theme();
	
}
function adm_action_f_theme_clone() { eval(scg());
	echo "<h1>Clone $thm theme</h1>";
	$sample="themes/$thm/t.sample.png";
		if(file_exists("$RFS_SITE_PATH/$sample")) {
			echo sc_picthumb("$RFS_SITE_PATH/$sample",300,0,0);
		}
	sc_bf(	"$RFS_SITE_URL/admin/adm.php",
			"action=f_theme_clone_go".$RFS_SITE_DELIMITER.
			"thm=$thm".$RFS_SITE_DELIMITER.
			"SHOW_CLEARFOCUSTEXT_50#50#new_name=Enter cloned theme name",
			"",
			"",
			"",
			"",
			"",
			"",
			100,
			"Clone" );
}
function adm_action_f_theme_edit() { eval(scg());
	echo "<h1>Editing theme [$thm]</h1>";
	sc_button("$RFS_SITE_URL/admin/adm.php?action=theme","Themes list");
	echo "<hr>";	
	$folder="$RFS_SITE_PATH/themes/$thm";
	echo "Elements of $folder <br>";
	$d = opendir($folder);
	while(false!==($entry = readdir($d))) {
		if(($entry != '.') && ($entry != '..') && (!is_dir($dir.$entry)) ) {
			if($entry[0]=="t") {
				$ft=sc_getfiletype($entry);
				switch($ft) {
					case "css":
						if($entry=="t.css") {
							echo "<hr>";
							echo "<h1>$entry</h1>";
							echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_edit_t_css&thm=$thm&tcss=$entry\">edit this file</a>]<br>";
							sc_css_edit_form(	"$folder/$entry",
												"$RFS_SITE_URL/admin/adm.php",
												"f_theme_edit_css",
												"thm=$thm");
						}
						break;
					case "php":
						if($entry=="t.php") {
							echo "<hr>";
							echo "<h1>$entry</h1>";
							echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_edit_t_php&thm=$thm&tphp=$entry\">edit this file</a>]<br>";
							sc_php_edit_form(		"$folder/$entry",
													"$RFS_SITE_URL/admin/adm.php",
													"f_theme_edit_php",
													"thm=$thm"
													);
						}
						else {
						}
						break;
					default: 
						break;
					
				}
			}
		}
	}	
	closedir($d);
	
	$d = opendir($folder);
	while(false!==($entry = readdir($d))) {
		if(($entry != '.') && ($entry != '..') && (!is_dir($dir.$entry)) ) {
			if($entry[0]=="t") {
				$ft=sc_getfiletype($entry);				
				switch($ft) {					
					case "gif":
					case "jpg":
					case "png":					
						$img="$RFS_SITE_URL/themes/$thm/$entry";
						echo "<hr>";
						echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_edit_delete&thm=$thm&dfile=$entry\" >
						<img src=\"$RFS_SITE_URL/images/icons/Delete.png\" border=0 width=16></a>";						
						echo "$img:<br>";
						echo "<img src=\"$img\"><br>";
						break;
					default: 
						break;
					
				}
			}
		}
	}	
	closedir($d);
	
	
	include( "footer.php" );
	exit();
}
function adm_action_f_theme_view_classes() { eval(scg());
	$file="$RFS_SITE_PATH/classes.out.txt";
	echo $file."<BR>";
	echo "<pre>";
	include($file);
	echo "</pre>";
	adm_action_theme();
}
function adm_action_theme() { eval(scg());

	echo "<h1>Theme Editor</h1>";
	

	sc_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_view_classes","View CSS Classes");
	echo "<hr>";
	
	$thms=sc_get_themes();
	while(list($key,$thm)=each($thms)) {
		
		echo " <div  style=\"float: left; height: 150px; margin: 20px; padding: 10px;\">";
	
	
		echo ucwords("<h3> $thm </h3>");
		$sample="themes/$thm/t.sample.png";
		if(file_exists("$RFS_SITE_PATH/$sample")) {
			
			echo sc_picthumb("$RFS_SITE_PATH/$sample",100,80,0);
		}
	
		echo "<br><hr>";
		echo "<div>";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_edit&thm=$thm\">edit</a>] ";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_clone&thm=$thm\">clone</a>] ";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_delete&thm=$thm\">delete</a>] ";
		echo "</div>";
		echo "</div>";
		
	}
	
	echo "<div style=\"clear: both;\" ></div>";
	
	include( "footer.php" );
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_SITE VARS
function adm_action_f_addsitevar() { eval( scg() );
	$name=strtolower( $name );
	$name=str_replace( " ","_",$name );
	$val=addslashes( $_REQUEST['val'] );
	sc_query( "insert into `site_vars` 			(`name`, `type`, `value`, `desc`)
	
										values ('$name', '$type', '$val', '$desc')" );
	
	adm_action_edit_site_vars();
}
function adm_action_f_upsitevar() { eval( scg() );
	$sv=mfo1("select * from site_vars where id=$id");
	$x=strtoupper($sv->name);
	echo "[\$RFS_SITE_$x] set to [$val] <br>";
	$val=addslashes( $_REQUEST['val'] );
	sc_query("update `site_vars` set `type`='$type' where id='$id'");
	sc_query("update `site_vars` set `value`='$val' where id='$id'");
	sc_query("update `site_vars` set `desc`='$desc' where id='$id'");
	adm_action_edit_site_vars();
}
function adm_action_f_delsitevar_go() { eval( scg() );
	sc_query( "delete from `site_vars` where `id`='$id'" );
	adm_action_edit_site_vars();
}
function adm_action_f_delsitevar() { eval(scg());
	$site_var=mfo1("select * from site_vars where id='$id'");
	sc_confirmform("<p>Delete \$RFS_SITE_$site_var->name ?</p>","$RFS_SITE_URL/admin/adm.php?action=f_delsitevar_go","id=$id");
	adm_action_edit_site_vars();
}
function adm_action_edit_site_vars() { eval( scg() );

	echo "<h3>Edit Site Variables</h3>";

	// TODO: Move these alterations into install
	sc_query("ALTER TABLE `site_vars` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
	sc_query("ALTER TABLE `site_vars` ADD `desc` TEXT");
	sc_query("ALTER TABLE `site_vars` ADD `type` TEXT");
	
	echo "<p>These variables will be loaded into global scope.</p>";
	
	echo "<table border=0>";
	
	echo "<tr><th>Variable</th><th>Type</th><th>Value</th><th></th><th></th></tr>";
	$res=sc_query( "select * from site_vars order by name" );
	for( $i=0; $i<mysql_num_rows( $res ); $i++ ) {
		$site_var=mysql_fetch_object( $res );
		echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\" enctype=\"application/x-www-form-URLencoded\">";
		echo "<tr><td>";
		echo "<input type=hidden name=action value=\"f_upsitevar\">";
		echo "<input type=hidden name=id value=\"$site_var->id\">";
		$site_var->name=strtoupper(stripslashes(($site_var->name)));
		echo "\$RFS_SITE_$site_var->name <br>$site_var->desc";
		echo "</td><td>";
		echo "<select name=\"type\" onchange=\"form.submit();\">";
		
		if(empty($site_var->type)) $site_var->type="text";
		echo "<option>$site_var->type";
		
		echo "<option>text";
		echo "<option>bool";
		echo "<option>file";
		echo "<option>theme";
		echo "<option>number";
		echo "<option>password";
		echo "</select>";
		
		echo "</td><td>";	
		
		$site_var->value=stripslashes( $site_var->value );
		
		switch($site_var->type) {
		case "bool":
			echo "<select name=val onchange=\"form.submit();\">";			
			if(sc_yes($site_var->value)) {
				echo "<option>on";
				echo "<option>off";
			}
			else {
				echo "<option>off";
				echo "<option>on";
			}
			echo "</select>";
			break;
			
		case "theme":
			echo "<select name=\"val\" onchange=\"form.submit();\">";
			$thms=sc_get_themes();
			while(list($key,$thm)=each($thms)){
				echo "<option";
				if($thm==$site_var->value) echo " selected=selected";
				echo ">".$thm;
			}
			echo "</select>";
			break;
			
		case "file":		
			echo "<input name=val size=40 value=\"$site_var->value\" onblur=\"form.submit();\">";
			if(!file_exists($site_var->value)) {
				echo sc_red()."<br>FILE DOES NOT EXIST";
			}
			
			break;
		
		default:		
			echo "<input name=val size=40 value=\"$site_var->value\" onblur=\"form.submit();\">";
			break;
		
		}
		echo "</td>";
		echo "</form>";
		echo "<td>";
		rfs_db_element_edit("","$RFS_SITE_URL/admin/adm.php","edit_site_vars","site_vars", $site_var->id);
		echo "</td></tr>";
	}
	echo "</table>";
	
	
	
	$rfsvars=file_get_contents("admin/rfsvars_out.txt");
	
	$rfsvars_opt=str_replace('$',"<option>",$rfsvars);
	
	

	echo "<select name=existing>";
	echo $rfsvars_opt;
	echo "</select>";
	
	
	echo "<div class=\"forum_box\">";
	echo "<table>";
	echo "<tr><td>";
	echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=\"f_addsitevar\">";
	echo "<input type=hidden name=id value=\"$site_var->id\">";
	echo "\$RFS_SITE_<input name=name size=17 value=\"ADD NEW\"> ";
	echo "<select name=\"type\">";		
		echo "<option>text";
		echo "<option>bool";
		echo "<option>file";
		echo "<option>theme";
		echo "<option>number";
		echo "<option>password";
		echo "</select>";
	echo "<input name=val size=40 value=\"INITIAL VALUE\">";
	echo "<br><textarea cols=100 name=desc>DESCRIPTION OF THIS VARIABLE</textarea>";
	echo "<input type=submit value=\"go\">";
	echo "</form>";
	echo "</td><td>";
	echo "</td></tr>";
	echo "</table>";
	echo "</div>";
	
	
	
	
	
	// foreach($GLOBALS as $k => $v)  { if(strtolower(substr($k,0,8))=="rfs_site") echo "[$k]<br>"; }
	
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_MENU ADMIN
function adm_action_f_admin_menu_change_icon() { eval( scg() );
	$_SESSION['select_image_path']="";
	sc_selectimage( "images","admin/adm.php","", "admin_menu", $id, "icon" );
}
function adm_action_f_admin_menu_edit_del_go() { eval( scg() );
	$res=sc_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
	echo "<h3>Edit Admin Menu :: Delete $menuitem->name :: DELETED!</h3>";
	sc_query( "delete from admin_menu where `id`='$id'" );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_del() { eval( scg() );
	$res=sc_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
	echo "<h3>Edit Admin Menu</h3>";
	echo "<table class=warning><tr><td>";
	echo smiles( "Delete $menuitem->name ^X" );
	echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=f_admin_menu_edit_del_go>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=submit name=submit value=confirm></form>";
	echo "</td></tr></table>";
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}


function adm_action_f_menu_admin_add_link() { eval(scg());
	echo "<h3>Edit Admin Menu :: Add $lname</h3>";
	echo $lname."<br>";
	echo $lurl."<br>";	
	global $mname;
	$mname=$lname;
	global $murl;
	$murl=$lurl;
	global $mcategory;
	$mcategory="unsorted";
	adm_action_f_admin_menu_edit_add();
}

function adm_action_f_admin_menu_edit_add() { eval( scg() );
	echo "<h3>Edit Admin Menu :: Add $mname</h3>";
	if(empty($mname))		$mname		= addslashes( $_REQUEST['mname'] );
	if(empty($murl)) 		$murl		= addslashes( $_REQUEST['murl'] );
	if(empty($mtarget))	$mtarget	= addslashes( $_REQUEST['mtarget'] );
	
	if(empty($mcategory)) {
		$mcat=mfo1("select * from categories");
		$mcategory=$mcat->name;
	}
		
	$q="INSERT INTO `admin_menu`	 (`category`,      `name`,    `icon`,    `url`,     `target`)
							    VALUES ('$mcategory',  '$mname',  '$micon',    '$murl',   '$mtarget') ;";
	echo $q."<BR>";
	sc_query( $q );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();

}
function adm_action_f_admin_change_category() { eval( scg() );
	echo "id: $id<br>";
	echo "cat: $name<br>";
	sc_query( "update admin_menu set `category`='$name' where `id`='$id'" );
    if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_mod() { eval( scg() );
	$res=sc_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
    if(empty($mname)) $mname=$name;
	echo "<h3>Edit Admin Menu :: Modify $menuitem->name = $mname</h3>";
	if( empty( $mname ) )       $mname=$menuitem->name;
	if( empty( $murl ) )        $murl =$menuitem->url;
	if( empty( $micon ) )       $micon=$menuitem->icon;
	if( empty( $mtarget ) )     $mtarget=$menuitem->target;
	if( empty( $mcategory ) )   $mcategory=$menuitem->category;
	echo $murl;
	sc_query( "update admin_menu set `name`='$mname' where `id`='$id'" );
	sc_query( "update admin_menu set `url`='$murl' where `id`='$id'" );
	sc_query( "update admin_menu set `icon`='$micon' where `id`='$id'" );
	sc_query( "update admin_menu set `target`='$mtarget' where `id`='$id'" );
	sc_query( "update admin_menu set `category`='$mcategory' where `id`='$id'" );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_entry_data($inid,$tdlc) { eval(scg());
        $id=$inid;
        d_echo("EDITING ADMIN MENU ENTRY: $id");
        $menuitem=mfo1( "select * from admin_menu where id='$id'" );

          echo "<tr>";
			echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<input type=hidden name=action value=f_admin_menu_edit_del>";
			echo "<input type=hidden name=id value=$menuitem->id>";

			echo "<div class=redbutton><input type=submit name=submit value=delete></div>";

			echo "</form>";

			echo "</td>";
			echo "<form method=\"post\" enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\">";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<input type=hidden name=action value=f_admin_menu_edit_mod>";

			echo "<input type=hidden name=id value=$menuitem->id>";
			echo "<input size=20 type=text name=mname value=\"$menuitem->name\">";
			echo "</td>";

			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<Select name=mcategory>";
			if( !empty( $menuitem->category ) )
				echo "<option>$menuitem->category";

			$cres=sc_query( "select * from categories order by name" );
			$j3=mysql_num_rows( $cres );

			for( $i3=0; $i3<$j3; $i3++ ) {
				$c=mysql_fetch_object( $cres );
				echo "<option>$c->name";
			}
			echo "</select>";

			echo "</td>";

			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<input size=40 type=text name=murl value=\"$menuitem->url\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";


			echo "<a href='adm.php?action=f_admin_menu_change_icon&id=$menuitem->id'>";

			echo "<img src=\"$RFS_SITE_URL/$menuitem->icon\" width=64 height=64 border='0'><br>Change</a>";



			echo "<input size=40 type=text name=micon value=\"$menuitem->icon\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<input type=text name=mtarget value=\"$menuitem->target\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<div class=menutop><input type=submit name=submit value=modify></div>";



			echo "</td>";
			echo "</form>";
			echo "</tr> ";
}
function adm_action_f_admin_menu_edit_entry() { eval(scg());
	echo "<h3>Edit Admin Menu item $id</h3>";
	
	sc_bf(  "$RFS_SITE_URL/admin/adm.php",
            "action=f_admin_menu_edit_mod".$RFS_SITE_DELIMITER."id=$id",
            "admin_menu",
            "select * from admin_menu where `id`='$id'",
            "", "id", "omit", "", 60, "Modify" );
			
/* sc_bf(  "$RFS_SITE_URL/admin/adm.php",
	       "action=f_edit_users_go".$RFS_SITE_DELIMITER.	       "id=$id",
	       "users",
	       "select * from users where `id`='$id'",
	       "",
	       "id".$RFS_SITE_DELIMITER.	       "first_login".$RFS_SITE_DELIMITER.	       "last_activity".$RFS_SITE_DELIMITER.	       "last_login".$RFS_SITE_DELIMITER.	       "logins",
	       "omit",
	       "",
	       60,
	       "update" );            */
    /*
    echo "<table border=0 cellspacing=0 cellpadding=0>";
	echo "<tr>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "<td class=contenttd> Name </td>";
	echo "<td class=contenttd> Category </td>";
	echo "<td class=contenttd> URL </td>";
	echo "<td class=contenttd> Icon </td>";
	echo "<td class=contenttd> Target </td>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "</tr>";
    adm_action_f_admin_menu_edit_entry_data($id,$tdlc);
    echo "</table>";
    */

    if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_admin_menu_edit() { eval( scg() );
	echo "<h3>Edit Admin Menu</h3>";
	echo "<table border=0 cellspacing=0 cellpadding=0>";
	echo "<tr>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "<td class=contenttd> Name </td>";
	echo "<td class=contenttd> Category </td>";
	echo "<td class=contenttd> URL </td>";
	echo "<td class=contenttd> Icon </td>";
	echo "<td class=contenttd> Target </td>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "</tr>";


	echo "<tr >";
	echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=f_admin_menu_edit_add>";
	echo "<td>";
	echo "</td>";

	echo "<td>";
	echo "<input size=20 name=mname>";
	echo "</td>";


	echo "<td>";
	echo "<Select name=mcategory>";

	$cres=sc_query( "select * from categories order by name" );
	$j3=mysql_num_rows( $cres );
	for( $i3=0; $i3<$j3; $i3++ ) {
		$c=mysql_fetch_object( $cres );
		echo "<option>$c->name";
	}
	echo "</select>";

	echo "</td>";


	echo "<td>";
	echo "<input size=40  name=murl>";
	echo "</td>";
	echo "<td>";
	echo "<input size=40 name=micon>";
	echo "</td>";
	echo "<td>";
	echo "<input name=mtarget>";
	echo "</td>";
	echo "<td >";
	echo "<div class=menutop><input type=submit name=submit value=add></div>";

	echo "</td>";
	echo "</form>";
	echo "</tr>";

	$cresz=sc_query( "select * from categories order by name asc" );
	$ccount=mysql_num_rows( $cresz );
	for( $ci=0; $ci<$ccount; $ci++ ) {
		$cc=mysql_fetch_object( $cresz );
		$res=sc_query( "select * from admin_menu where category = '$cc->name' order by name asc" );
		$count=mysql_num_rows( $res );
		for( $i=0; $i<$count; $i++ ) {
			$menuitem=mysql_fetch_object( $res );
			$tdlc++;
			if( $tdlc==2 ) $tdlc=0;
            adm_action_f_admin_menu_edit_entry_data($menuitem->id,$tdlc);
/*			echo "<tr>";
			echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/admin/adm.php>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<input type=hidden name=action value=f_admin_menu_edit_del>";
			echo "<input type=hidden name=id value=$menuitem->id>";

			echo "<div class=redbutton><input type=submit name=submit value=delete></div>";

			echo "</form>";

			echo "</td>";
			echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/admin/adm.php>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<input type=hidden name=action value=f_admin_menu_edit_mod>";

			echo "<input type=hidden name=id value=$menuitem->id>";
			echo "<input size=20 type=text name=mname value=\"$menuitem->name\">";
			echo "</td>";

			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<Select name=mcategory>";
			if( !empty( $menuitem->category ) )
				echo "<option>$menuitem->category";

			$cres=sc_query( "select * from categories order by name" );
			$j3=mysql_num_rows( $cres );

			for( $i3=0; $i3<$j3; $i3++ ) {
				$c=mysql_fetch_object( $cres );
				echo "<option>$c->name";
			}
			echo "</select>";

			echo "</td>";

			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<input size=40 type=text name=murl value=\"$menuitem->url\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";


			echo "<a href='adm.php?action=f_admin_menu_change_icon&id=$menuitem->id'>";

			echo "<img src=\"$RFS_SITE_URL/$menuitem->icon\" width=64 height=64 border='0'><br>Change</a>";



			echo "<input size=40 type=text name=micon value=\"$menuitem->icon\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";
			echo "<input type=text name=mtarget value=\"$menuitem->target\">";
			echo "</td>";
			echo "<td class=sc_project_table_$tdlc valign=bottom>";

			echo "<div class=menutop><input type=submit name=submit value=modify></div>";



			echo "</td>";
			echo "</form>";
			echo "</tr> ";
            */

		}
	}
	echo "</table>";
	echo "<br><br>";
	include( "footer.php" );
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_MENU TOP
function adm_action_registered_menu_items() { 
	sc_show_menu_options();
	finishadminpage();
}
function adm_action_f_menu_topedit_del_go() { eval( scg() );
	$res=sc_query( "select * from menu_top where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
	echo "<h3>Edit Top Menu :: Delete $menuitem->name :: DELETED!</h3>";
	sc_query( "delete from menu_top where `id`='$id'" );
	// adm_action_menu_topedit();
	sc_show_menu_options();
	exit();
}
function adm_action_f_menu_topedit_del() { eval( scg() );
	$res=sc_query( "select * from menu_top where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
	echo "<h3>Edit Top Menu :: Delete $menuitem->name</h3>";
	echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=f_menu_topedit_del_go>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=submit name=submit value=confirm></form>";
	// adm_action_menu_topedit();
	sc_show_menu_options();
	exit();
}
function adm_action_f_menu_top_add_link() { eval(scg());
	echo "<h3>Edit Top Menu :: Add $lname</h3>";
	echo $lname."<br>";
	echo $lurl."<br>";	
	global $mname;
	$mname=$lname;
	global $menu_url;
	$menu_url=$lurl;
	global $msor;
	$msor=9999;
	adm_action_f_menu_topedit_add();
}
function adm_action_f_menu_topedit_add() { eval( scg());
	echo "<h3>Edit Top Menu :: Add $mname</h3>";
	
	echo "$mname <br>";
	echo "$menu_url<br>";
	echo "$target<br>";
	echo "$msor<br>";
	echo "$access<br>";
	
	sc_query( "insert into menu_top (   `name`,      `link`, `target`, `sort_order`, `access`)
								  values('$mname', '$menu_url', '$target',     '$msor', '$access');" );
	// adm_action_menu_topedit();
	sc_show_menu_options();
	exit();
}
function adm_action_f_menu_topedit_mod() { eval( scg() );
	$res=sc_query( "select * from menu_top where `id`='$id'" );
	$menuitem=mysql_fetch_object( $res );
	echo "<h3>Edit Top Menu :: Modify $menuitem->name = $mname</h3>";
	sc_query( "update menu_top set `name`='$mname' where `id`='$id'" );
	sc_query( "update menu_top set `link`='$menu_url' where `id`='$id'" );
	sc_query( "update menu_top set `target`='$target' where `id`='$id'" );
	sc_query( "update menu_top set `sort_order`='$msor' where `id`='$id'" );
	sc_query( "update menu_top set `access`='$access' where `id`='$id'" );
	// adm_action_menu_topedit();
	sc_show_menu_options();
	exit();
}
function adm_action_menu_topedit() { eval( scg() );
	echo "<h3>Edit Top Menu</h3>";
	
	echo "<table border=0 cellspacing=0 cellpadding=0>";
	echo "<tr>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "<td class=contenttd> name </td>";
	echo "<td class=contenttd> link </td>";
	echo "<td class=contenttd> target </td>";
	echo "<td class=contenttd> sort order </td>";
	echo "<td class=contenttd> access </td>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "</tr>";
	$res=sc_query( "select * from menu_top order by sort_order asc" );
	$count=mysql_num_rows( $res );
	for( $i=0; $i<$count; $i++ ) {
		$menuitem=mysql_fetch_object( $res );

		echo "<tr>";
		echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
		echo "<td class=contenttd>";

		echo "<input type=hidden name=action value=f_menu_topedit_del>";
		echo "<input type=hidden name=id value=$menuitem->id>";
		echo "<input type=submit name=submit value=delete>";
		
		

		echo "</td>";
		echo "</form>";

		echo "<form enctype=\"application/x-www-form-URLencode\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
		echo "<td class=contenttd>";
		
		echo "<input type=\"hidden\" name=\"action\" value=\"f_menu_topedit_mod\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$menuitem->id\">";
		echo "<input size=\"20\" type=text name=\"mname\" value=\"$menuitem->name\">";
		echo "</td>";
		
		echo "<td class=\"contenttd\">";
		echo "<input size=\"40\" type=\"text\" name=\"menu_url\" value=\"$menuitem->link\">";
		echo "</td>";
		
		echo "<td class=\"contenttd\">";
		echo "<input size=\"10\" type=\"text\" name=\"target\" value=\"$menuitem->target\">";
		echo "</td>";

		echo "<td class=\"contenttd\">";
		echo "<input size=\"10\" type=\"text\" name=\"msor\" value=\"$menuitem->sort_order\">";
		echo "</td>";

		echo "<td class=\"contenttd\">";
		echo "<input size=\"10\" type=\"text\" name=\"access\" value=\"$menuitem->access\">";
		echo "</td>";
		
		echo "<td class=\"contenttd\">";
		echo "<input type=\"submit\" name=\"submit\" value=\"modify\">";
		echo "</form>";
		echo "</td></tr>";
	}

	echo "<tr>";

	echo "<form enctype=application/x-www-form-URLencoded method=\"post\" action=adm.php>";
	echo "<input type=hidden name=action value=f_menu_topedit_add>";
	echo "<td class=contenttd>";
	echo "</td>";
	echo "<td class=contenttd>";
	echo "<input size=20 name=mname>";
	echo "</td>";
	echo "<td class=contenttd>";
	echo "<input size=40  name=menu_url>";
	echo "</td>";	
	echo "<td class=contenttd>";
	echo "<input size=10  name=target>";
	echo "</td>";
	
	echo "<td class=contenttd>";
	echo "<input size=10 name=msor>";
	echo "</td>";
	echo "<td class=contenttd>";
	echo "<input size=10 name=access>";
	echo "</td>";
	echo "<td class=contenttd>";
	echo "<input type=submit name=submit value=add>";
	echo "</form>";
	echo "</td>";

	echo "</tr>";
	echo "</table>";
	echo "<br><br>";

	include( "footer.php" );
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_CATEGORIES
function adm_action_f_category_change_icon() {
	eval( scg() );
	$_SESSION['select_image_path']="";
	sc_selectimage( "images","admin/adm.php","edit_categories", "categories", $id, "image" );
	exit();
}
function adm_action_f_delete_category() {
	eval( scg() );
	echo "<p>Category $category deleted</p>";
	sc_query( "delete from categories where `name`='$category'" );
	adm_action_edit_categories();
}
function adm_action_f_add_category() {
	eval( scg() );
	echo "<p>Added category $category</p>";
	sc_query( "insert into categories (`name`, `image`, `worksafe` ) values ('$category', '$image', '$worksafe')" );
	adm_action_edit_categories();
}
function adm_action_f_rename_category() {
	eval( scg() );
	echo "<p>Renamed category from $category to $newname</p>";
	sc_query( "update categories set image='$image' where name = '$category'" );
	sc_query( "update categories set name='$newname' where name = '$category'" );
	sc_query( "update categories set worksafe='$worksafe' where name = '$category'");
	sc_query( "update admin_menu set category = '$newname' where category = '$category'" );
	adm_action_edit_categories();
}
function adm_action_edit_categories() {
	eval( scg() );
	
	// sc_database
	sc_database_add("categories","worksafe", "text", "NOT NULL");
	
	echo "<h3>Edit Categories (aka tags)</h3>";
	$result=sc_query( "select * from categories order by name asc" );
	$numcats=mysql_num_rows( $result );
	if( $numcats==0 ) echo "<p>There are no categories!</p>\n";
	echo "<table border=0>";

	echo "


	<tr>

	<td>&nbsp;</td>
	<td>
	<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">
	<input type=hidden name='action'   value='f_add_category'>
	<input type=text   name='category' value='' style=' width: 100%;'>
	</td>

	<td>
	<img src=$RFS_SITE_URL/images/icons/exclamation.png width=64 height=64 border='0'>
	</td>
	<td>
	<input type=text name=image value=''>
	</td>
	
	<td>
	<input type=text name=worksafe value=''>
	</td>
	
	<td>
	<div class=menutop>
	<input type=submit name=submit        value=add>
	</div>

	</form>
	</td>

	</tr>

	";

	for( $i=0; $i<$numcats; $i++ ) {
		$cat=mysql_fetch_object( $result );
		echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\"><tr><td>";
		echo "<input type=hidden name=action    value=f_delete_category>\n";
		echo "<input type=hidden name=category  value=\"$cat->name\">\n";
		echo "<div class=menutop>";
		echo "<input type=submit name=submit    value=delete>\n";
		echo "</div>";
		echo "</form></td>\n";
		echo "<td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action    value=f_rename_category>\n";
		echo "<input type=hidden name=category  value=\"$cat->name\">\n";
		echo "<input size=40 type=text   name=newname  value=\"$cat->name\">\n";
		if(empty($cat->image)) $cat->image="images/icons/exclamation.png";
		if(!file_exists("$RFS_SITE_PATH/$cat->image")) {
			$cat->image="images/icons/404.png";
		}
		echo "</td>
		<td>
		<a href='$RFS_SITE_URL/admin/adm.php?action=f_category_change_icon&id=$cat->id'>		
		<img src='$RFS_SITE_URL/$cat->image' border='0' width=64 height=64> </a>
		</td>
		<td>
		<input type=text name=image value='$cat->image'>
		</td>
		
		<td>
		<input type=text name=worksafe value='$cat->worksafe'>
		</td>

		<td>\n";
		echo "<div class=menutop>";
		echo "<input type=submit name=submit    value=Update></form>\n";
		echo "</div>";
		echo "</td></tr>\n";
	}
	echo"</table>";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_USER EDIT
function adm_action_f_edit_users_go() {
	eval( scg() );
	echo "<h3>User updated</h3>";
	sc_updb( "users","id",$id,1 );
	adm_action_user_edit();
}
function adm_action_f_edit_users() { eval( scg() );

	echo "WHAT";

	$res=sc_query( "select * from users where `id`='$id'" );
	$user=mysql_fetch_object( $res );
	
	
	echo "<h3>Editing User [$user->name]</h3>";
	
	sc_bf( "$RFS_SITE_URL/admin/adm.php",
	       "action=f_edit_users_go".$RFS_SITE_DELIMITER.
	       "id=$id",
	       "users",
	       "select * from users where `id`='$id'",
	       "",
	       "id".$RFS_SITE_DELIMITER.
	       "first_login".$RFS_SITE_DELIMITER.
	       "last_activity".$RFS_SITE_DELIMITER.
	       "last_login".$RFS_SITE_DELIMITER.
	       "logins",
	       "omit",
	       "",
	       60,
	       "update" );
		   
	include("footer.php");
	exit();
}
function adm_action_f_del_users_go() {
	eval( scg() );
	$res=sc_query( "select * from users where `id`='$id'" );
	$user=mysql_fetch_object( $res );
	if( $yes=="Yes" ) {
		echo "User $user->name removed from database";
		sc_query( "delete from users where `id`='$id'" );
	}
	adm_action_user_edit();
}
function adm_action_f_del_users() {
	eval( scg() );
	echo smiles( "<p class=warning>^X<br>WARNING!<BR></p>" );
	$res=sc_query( "select * from users where `id`='$id'" );
	$user=mysql_fetch_object( $res );
	sc_confirmform( "Delete $user->name?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_del_users_go".$RFS_SITE_DELIMITER."id=$id" );
	include("footer.php");
	exit();
}
function adm_action_f_add_user() {
	eval( scg() );
	echo "<h3>Add user $name</h3>";
	$pmd5=md5( $pass );
	sc_query( "insert into `users` (`name`,`pass`) VALUES ('$name','$pmd5');" );
	adm_action_user_edit();
}
function adm_action_user_edit() {
	eval( scg() );

    echo "<h1>User Editor</h1>";
	echo "<h2>Add User</h2>";

	sc_bf(  "$RFS_SITE_URL/admin/adm.php",
            "action=f_add_user",
            "users",
            "",
            "",
            "name".$RFS_SITE_DELIMITER."pass",
            "include",
            "",
            20,
            "add new user" );

	sc_db_dumptable( "users",
                     "showform".$RFS_SITE_DELIMITER."f_",
                     "id",
                     "" );

	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_RSS EDITOR
function adm_action_f_rss_edit_go_edit() {
	eval( scg() );
	if( $update=="update" ) sc_query( "UPDATE rss_feeds SET `feed`='$edfeed' where `id`='$oid'" );
	if( $delete=="delete" ) sc_query( "DELETE FROM rss_feeds WHERE id = '$oid' " );
	adm_action_rss_edit();
}
function adm_action_f_rss_edit_go_add() {
	eval( scg() );
	sc_query( "insert into rss_feeds values('$edfeed',0);" );
	adm_action_rss_edit();
}
function adm_action_rss_edit() {
	eval( scg() );
	$result=sc_query( "select * from rss_feeds" );
	$num_feeds=mysql_num_rows( $result );
	echo "<h3>Editing RSS Feeds </h3>";

	for( $i=0; $i<$num_feeds; $i++ ) {
		echo "<table border=0 cellspacing=0 cellpadding=0>\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=rsseditgoedit>\n";
		$feed=mysql_fetch_object( $result );
		echo "<tr><td>Feed URL</td> <td><input type=textbox name=edfeed value=\"$feed->feed\" size=100></td>\n";
		echo "<td><input type=submit value=delete name=delete></td>\n";
		echo "<td><input type=submit value=update name=update> <input type=hidden value=$feed->id name=oid></td>\n";
		echo "</tr>\n";
		echo "</form></table>\n";
	}
	echo "<table border=0 cellspacing=0 cellpadding=0>\n";
	echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
	echo "<input type=hidden name=action value=rsseditgoadd>\n";
	echo "<tr><td>New Feed</td><td><input type=textbox name=edfeed value=\"\" size=100></td>\n";
	echo "<td><input type=submit value=add name=add></td>\n";
	echo "</form></table>\n";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_SMILEY EDITOR
function adm_action_edit_smilies() {
	eval( scg() );
	echo "<h3>Smiley Editor</h3>";
	$sto=stripslashes( $sto );
	$sfrom=stripslashes( $sfrom );
	$ofrom=stripslashes( $ofrom );
	switch( $smact ) {
		case "update":
			$sfrom=addslashes( $sfrom );
			rfs_echo("$sfrom -> $sto updated");
			$sto=addslashes( $sto );
			sc_query( "UPDATE `smilies` SET `sto`='$sto' where `sfrom`='$ofrom';" );
			sc_query( "UPDATE `smilies` SET `sfrom`='$sfrom' where `sfrom` = '$ofrom';" );
			break;
		case "delete":
			$sfrom=addslashes( $sfrom );
			echo "$sfrom -> $sto deleted";
			$sto=addslashes( $sto );
			sc_query( "delete from `smilies` where `sfrom` = '$sfrom' and `sto` = '$sto' limit 1;" );
			break;
		case "new":
			$sfrom=addslashes( $sfrom );
			echo "$sfrom -> $sto created";
			$sto=addslashes( $sto );
			sc_query( "INSERT INTO `smilies` VALUES ('$sfrom', '$sto');" );
			break;
	}
	echo "<table width=100% cellspacing=0 cellpadding=0 class=\"dm_news\">\n";
	echo "<tr>\n";
	echo "<td class=contenttd>";
	$result=sc_query( "select * from smilies" );
	$num_smilies=mysql_num_rows( $result );
	echo "<td class=contenttd>\n";
	echo "$num_smilies smilies";
	echo "<table border=0 cellspacing=0 cellpadding=5 width=100%>";
	for( $i=0; $i<$num_smilies; $i++ ) {
		$bg=$bg+1;
		if( $bg>2 ) $bg=1;
		$smiley = mysql_fetch_array( $result );
		$sfrom=stripslashes( $smiley['sfrom'] );
		$sto=stripslashes( $smiley['sto'] );
		echo "<tr>\n";
		rfs_echo( "<td class=sc_project_table_$bg align=center width=24 valign=top>$sto</td>");
		echo "<td class=sc_project_table_$bg valign=top>\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=edit_smilies>\n";
		echo "<input type=hidden name=smact value=update>";
		echo "<input type=hidden name=ofrom value=\"$sfrom\">\n";
		echo "</td>";
		echo "<td class=sc_project_table_$bg>";
		echo "<input size=5 type=textbox name=sfrom value=\"$sfrom\">\n";
		echo "</td>";
		echo "<td class=sc_project_table_$bg>";
		echo "<textarea name=sto cols=80>$sto</textarea>\n";
		echo "</td>";
		echo "<td class=sc_project_table_$bg>";
		echo "<input type=submit name=smact value=update></form></td>";
		echo "<td class=sc_project_table_$bg>";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=edit_smilies>\n";
		echo "<input type=hidden name=smact value=delete>";
		echo "</td>";
		echo "<td class=sc_project_table_$bg>";
		echo "<input type=hidden name=sfrom value=\"$sfrom\">\n";
		echo "<input type=hidden name=sto   value=\"".urlencode( $sto )."\">\n";
		echo "<input type=submit name=delete value=delete>";
		echo "</form></td>\n";
		echo "</tr>";
	}

	echo "<tr>\n";
	echo "<td class=contenttd align=center width=24 valign=top>Add";
	echo "</td>";
	echo "<td class=contenttd>";

	echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
	echo "<input type=hidden name=action value=edit_smilies>\n";
	echo "<input type=hidden name=smact value=new>\n";
	echo "</td>\n";
	echo "<td class=contenttd valign=top><input size=5 type=textbox name=sfrom></td>\n";
	echo "<td class=contenttd valign=top><textarea name=sto cols=80></textarea></td>\n";
	echo "<td class=contenttd valign=top><input type=submit  value=add></form></td>\n";
	echo "</tr></table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_LOG STUFF
function adm_action_f_domain_quiet() {
	eval( scg() );
	sc_query( "insert into `quiet` (`ip`,`domain`) values(\"$domain\",\"$domain\")" );
}
function adm_action_log_rotate() {
	eval( scg() );
	$dr=$RFS_SITE_PATH;
	$t=time();
	if( $RFS_SITE_PATH=="C:\\xampp\\htdocs\\sethcoder" ) {
		echo "rename $dr\\log\\log.htm $dr\\log\\log_$t.htm<BR>";
		system( "rename $dr\\log\\log.htm log_$t.htm" );
		echo "copy $dr\\log\\blanklog.htm $dr\\log\\log.htm<BR>";
		system( "copy $dr\\log\\blanklog.htm $dr\\log\\log.htm" );
	}  else  {
		echo "mv $dr/log/log.htm $dr/log/log_$t.htm";
		system( "mv $dr/log/log.htm $dr/log/log_$t.htm" );
		echo "cp $dr/log/blanklog.htm $dr/log/log.htm";
		system( "cp $dr/log/blanklog.htm $dr/log/log.htm" );
		sc_log( "Log restarted" );
	}
	adm_action_log_view();
}
function adm_action_log_view() {
	eval( scg() );
	echo "<h3>View Log</h3>";
	@include( "$RFS_SITE_PATH/log/log.htm" );
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_COUNTERS
function adm_action_counters() {
	echo "<h3>Counters</h3>\n";
	$hits_raw=$_REQUEST['hits_raw']; if(empty($hits_raw)) $hits_raw=50;
	echo "Showing pages with at least $hits_raw hits<br>";
	echo "<table>";
	$r=sc_query("select * from counters where hits_raw > $hits_raw");
	$n=mysql_num_rows($r);
	for($x=0;$x<$n;$x++){
		$counter=mysql_fetch_object($r);
		echo "<tr><td width='200'>";
		echo "$counter->user_timestamp ";
		echo "</td><td>";
		echo "$counter->last_ip";
		echo "</td><td>";
		echo "$counter->hits_raw";
		echo "</td><td>";
		echo "$counter->hits_unique ";
		echo "</td><td>";
		echo sc_trunc($counter->page,200);
		echo "</td><tr>";
		
	}
	echo "<table>";
	
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_AWARD EDIT
// function adm_action_awards_edit() { 	echo "<h3>Award Editor</h3>\n"; 	sc_awards_list(); 	include("footer.php");	exit();}

function adm_action_f_add_award_go() {
	eval( scg() );
	echo "<h3>Add award!</h3>\n";
	// id, name, description, image, time
	$name=addslashes( $name );
	$description=addslashes( $description );
	$image=addslashes( $image );
	$time=date( "Y-m-d H:i:s" );
	sc_query( "insert into awards values('', '$name', '$description', '$image', '$time')" );
	adm_action_awards_edit();
}
function adm_action_f_edit_award_go() {
	eval( scg() );
	echo "<h3>Edit award!</h3>\n";
	// id, name, description, image, time
	$name=addslashes( $name );
	$description=addslashes( $description );
	$image=addslashes( $image );
	sc_query( "update awards set name = '$name' where id = '$id'" );
	sc_query( "update awards set description = '$description' where id = '$id'" );
	sc_query( "update awards set image = '$image' where id = '$id'" );
	adm_action_awards_edit();
	
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_LINK EDIT
function adm_action_f_add_link() {
	eval( scg() );
	$link=$_REQUEST['link'];
	$time=date( "Y-m-d H:i:s" );
	if( $data->id==0 ) $data->id=999;
	sc_query( "insert into `link_bin` (`link`,`sname`,`time`,`bumptime`,`poster`,`description`)
	          values('$link','$sname','$time','$time',   '$id','$description')" );
	echo "<p>Link [$link][$sname] added to linkbin...</p>\n";
	sc_log( "*****> $data->name added a link to the linkbin [$link]" );
	adm_action_edit_linkbin();
}
function adm_action_f_modify_link() {
	eval( scg() );
	if( $deletelink=="delete" ) {
		$l=mfo1( "select * from link_bin where `id`='$linkid'" );
		sc_confirmform( "Are you sure you want to delete $l->link ?",
                        "$RFS_SITE_URL/admin/adm.php",
                        "action=f_modify_link".$RFS_SITE_DELIMITER."deletelink=delete_go".$RFS_SITE_DELIMITER."linkid=$linkid" );
	}
	if( $deletelink=="delete_go" ) {

		$l=mfo1( "select * from link_bin where `id`='$linkid'" );
		sc_query( "DELETE FROM link_bin where `id` = '$linkid' limit 1", $mysql );
		sc_log( "*****> $data->name deleted a link from the linkbin $l->short_name $l->link" );

		sc_info( "$l->link deleted from the link bin","white","red" );

		adm_action_edit_linkbin();
	}
	if( $renamelink=="modify" ) {
		echo "<p><h3>Modifying Link!</h3></p>\n";
		$short_name=addslashes( $short_name );
		$linkurl=addslashes( $linkurl );
		$description=addslashes( $description );
		$category=addslashes( $category );
		sc_query( "update link_bin set `sname` = '$short_name' where `id` = '$linkid'" );
		sc_query( "update link_bin set `link` = '$linkurl' where `id` = '$linkid'" );
		sc_query( "update link_bin set `description` = '$description' where `id` = '$linkid'" );
		$hide=0;
		if( $hidden=="yes" ) {
			$hide=1;
		}
		if( $hidden=="no" )  {
			$hide=0;
		}
		sc_query( "update link_bin set `friend` = '$friend' where `id` = '$linkid'");
		sc_query( "update link_bin set `hidden` = '$hide' where `id` = '$linkid'" );
		sc_query( "update link_bin set `referral` = '$referral' where `id` = '$linkid'" );
		sc_query( "update link_bin set `referrals` = '$referrals' where `id` = '$linkid'" );
		sc_query( "update link_bin set `clicks` = '$clicks' where `id` = '$linkid'" );
		sc_query( "update link_bin set `category` = '$category' where `id` = '$linkid'" );
		sc_query( "update link_bin set `rating` = '$rating' where `id` = '$linkid'" );
		adm_action_edit_linkbin();
	}
}
function adm_action_edit_linkbin() {
	eval( scg() );
	echo "<h3>Link Bin Editor</h3>\n";
	$result=sc_query( "select * from link_bin order by time desc" );
	$numlinks=mysql_num_rows( $result );
	echo "<table width=100% border=0 cellspacing=0 cellpadding=4 align=center>\n";
	$gt=2;
	for( $i=0; $i<$numlinks; $i++ ) {
		$gt++;
		if( $gt>2 )$gt=1;
		echo "<tr><td class=sc_project_table_$gt><br>\n";
		$link=mysql_fetch_object( $result );
		$userdata=sc_getuserdata( $link->poster );
		echo "<table border=0 cellspacing=0 cellpadding=0 width=100% >\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"f_modify_link\">\n";
		echo "<input type=\"hidden\" name=\"linkid\" value=\"$link->id\">\n";

echo "<tr class=sc_project_table_$gt>\n";

echo "<td class=sc_project_table_$gt>Short Name</td>";
echo "<td class=sc_project_table_$gt width=230><input type=text name=short_name value=\"$link->sname\" size=28></td>";

echo "<td class=sc_project_table_$gt>URL</td>";
echo "<td class=sc_project_table_$gt width=250><input type=text name=linkurl value=\"$link->link\" size=40> </td>\n";

echo "<td class=sc_project_table_$gt width=300>(submitted by $userdata->name on ".sc_time( $link->time ).")</td>\n";
echo "<td class=sc_project_table_$gt>Rating:</td>\n";
echo "<td class=sc_project_table_$gt width=100 align=center><input type=submit name=renamelink value=modify></td>\n";
echo "</tr>\n";

echo "<tr>\n";

echo "<td class=sc_project_table_$gt>Category</td>";

		echo "<td class=sc_project_table_$gt>\n";
		echo "<select name=category>\n";
		echo "<option>$link->category\n";

		$result2=sc_query( "select * from `categories` order by name asc" );
		$numcats=mysql_num_rows( $result2 );
		for( $i2=0; $i2<$numcats; $i2++ ) {
			$cat=mysql_fetch_object( $result2 );
			echo "<option>$cat->name\n";
		}

		echo "</select>\n";
		echo "</td>\n";

	echo "<td class=sc_project_table_$gt>Description</td>";
		echo "<td class=sc_project_table_$gt><input type=text name=description value=\"$link->description\" size=40></td>\n";
		echo "<td class=sc_project_table_$gt><table border=0><tr>\n";
		echo "<td class=sc_project_table_$gt>referrals</td><td class=sc_project_table_$gt><input type=text size=4 name=referrals value=\"$link->referrals\"></td>\n";
		echo "<td class=sc_project_table_$gt>clicks</td><td class=sc_project_table_$gt><input type=text size=4 name=clicks value=\"$link->clicks\"></td>\n";
		
		
		echo "<td class=sc_project_table_$gt>";
		
		
		if( sc_yes($link->hidden) ) echo "hidden <select name=hidden><option>yes<option>no</select>\n";
		else echo "hidden <select name=hidden><option>no<option>yes</select>\n";
		echo "<br>";
				
		if( sc_yes($link->friend) ) echo "friend <select name=friend><option>yes<option>no</select>\n";
		else echo "friend <select name=friend><option>no<option>yes</select>\n";		
		echo "<br>";
		
		if( sc_yes($link->referral) ) echo "referral <select name=referral><option>yes<option>no</select>\n";
		else echo "referral <select name=referral><option>no<option>yes</select>\n";		
		echo "<br>";
		
		echo "</tr></table></td>\n";
		echo "<td class=sc_project_table_$gt><select name=rating><option>$link->rating\n";
		for( $j=1; $j<6; $j++ ) echo "<option>$j\n";
		echo "</select></td>\n";
		echo "<td class=sc_project_table_$gt align=center><input type=submit name=deletelink value=delete></td>\n";

		echo "</tr>\n";
		echo "<tr><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</td></tr>\n";
	}

	echo "<tr><td>\n";
	echo "</td></tr></table>\n";
	// add a new link here...

	echo "<h2>Add Link</h2>\n";

	sc_bf(  "$RFS_SITE_URL/admin/adm.php", "action=f_add_link",
            "link_bin", "", "id",
            "sname".$RFS_SITE_DELIMITER."link".$RFS_SITE_DELIMITER."description",
            "include", "category",
            20, "add link" );
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_EDIT_TAGS
function adm_action_edit_tags() { eval(scg()); 

	echo "<h3>Edit Tags</h3>";
	lib_mysql_scrub("tags","tag");

	$r=sc_query("select * from tags order by tag asc");
	$n=mysql_num_rows($r);
	
	echo "<p>Hidden tags will not be shown to the public. They won't even show up in listings of available tags.</p>";
	
	echo "<div style='clear:both;' class='sc_file_table_$gt'  >";
		echo "<div style='float:left; width:170px;'>";		
			echo "Tag";
		echo "</div>";
		echo "<div style='float:left;'>";
			echo "Hidden";
		echo "</div>";
	echo "</div>";
	
	for($i=0;$i<$n;$i++) {
		$tag=mysql_fetch_object($r);
		$gt++;if($gt>1) $gt=0;		
		echo "<div style='clear:both;' class='sc_file_table_$gt'  >";
			echo "<div style='float:left;'>";		
				sc_ajax("Tag,80"			  	, "tags"  	,  "id",    "$tag->id",      "tag",       "", "nohide,nolabel",	"admin", "access", "");
			echo "</div>";
			echo "<div style='float:left;'>";
				sc_ajax("Hidden,30"		  	, "tags"  	,  "id",    "$tag->id",   "hidden",       "", "nohide,nolabel",	"admin", "access", "");
			echo "</div>";
		echo "</div>";
	}
	
	exit();

}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_DISK_FREE
function adm_action_disk_free() { eval(scg());
	echo "<div class='wikishell'>";	
	echo "<pre>";
	$x=array();
	array_push($x,"===================================================================");
	exec("df ",$x);
	array_push($x,"===================================================================");
	exec("df -h",$x);
	array_push($x,"===================================================================");
	foreach($x as $k => $v) 
		echo $v."\n";
	echo "</pre>";
	echo "</div>";
	
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_AJAX_TESTPAGE
function adm_action_ajax_testpage() { eval(scg());
	echo "<h3>Ajax test page</h3>";

sc_info("
rfs_ajax(data,size,properties,access,callback)<br>
data = table,key_field,key_field_value,field_to_modify<br>
size = width[,height]<br>
properties = [[type[,]][,nohide][,nolabel]<br>
access = access_type, access_action<br>
callback = [ajaxcallback][,javascript_callback]<br>
",white, green);

rfs_ajax("users,id,$data->id,webpage","","nohide","admin,access","");

// rfs_ajax("users,id,$data->id,alias","","select,categories
	
	
	
	
	
	exit();
}


///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_DEFAULT_ACTION
function adm_action_() { eval(scg());
	if($db_queries=="list" ) { adm_db_query_list();  exit(); }
		// sc_ajax($label,$table,$key,$kv,$field,$width,$type)
		// echo "<table border=0>";
		// sc_ajax("Avatar","users", "name", "$data->name","avatar", 60, "","admin","access","sc_ajax_callback_image");
		// 	sc_ajax("Last Name"	,"users","name","seth.parson","last_name","","","admin","access","");
		// sc_ajax("Email"		,"users","name","seth.parson","email",80,"","admin","access");
		// sc_ajax("File",      "files","name","amwifi.exe","name",60,"","files","edit");
		// echo "</table>";	

	sc_access_method_add("admin", "access");
	sc_access_method_add("admin", "categories");
	
	$ax=mfo1("select * from access where name='Administrator' and action='access' and page='admin'");
	if(!$ax->id) {
		sc_query("insert into access (name,action,page) values('Administrator','access','admin')");
	}
	

	if( !empty( $_GET['admed'] ) ) $_SESSION['admed']=$_GET['admed'];

	$data=sc_getuserdata( $_SESSION['valid_user'] );
	if(!sc_access_check("admin","access")) return;
	
	echo "<div align=center width=80%>";

	echo "<h1>Administration Panel</h1>";
	
	echo "Running RFS CMS version $RFS_VERSION ( BUILD $RFS_BUILD )";	
		
	if($RFS_SITE_CHECK_UPDATE=="true") {
			system("rm vercheck");
			system("rm buildcheck");
			system("wget -O vercheck https://raw.github.com/sethcoder/rfscms/master/include/version.php");
			system("wget -O buildcheck https://raw.github.com/sethcoder/rfscms/master/build.dat");
			$rver="remote version unknown"; 
			$file=fopen("vercheck", "r");  if($file) { $rver=fgets($file,256); fclose($file); }
			$file=fopen("buildcheck","r"); if($file) { $rbld=fgets($file,256); fclose($file); }
			system("rm vercheck");
			system("rm buildcheck"); 
			$rverx=explode("\"",$rver);
			if( ($RFS_VERSION!=$rverx[1]) ||
				 (intval($RFS_BUILD)!=intval($rbld))) {
				sc_inform("NEW VERSION AVAILABLE: ".$rverx[1]." BUILD $rbld");
			}
			else {
				echo "No new updates";
			}
	}
	
	
	echo "<br>";
	echo "<hr>";

    sc_info(exec("uptime"),"white","blue");

	echo "<table border=0><tr><td>";

	sc_button( "$RFS_SITE_URL/admin/adm.php?debug=on","Debug on <font style='color: green; background-color: dark-green;'> ON </font>" );
	sc_button( "$RFS_SITE_URL/admin/adm.php?debug=off","Debug <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";

	sc_button( "$RFS_SITE_URL/admin/adm.php?admed=on&what=1","Adm Edit <font style='color: green; background-color: dark-green;'> ON </font>" );
	sc_button( "$RFS_SITE_URL/admin/adm.php?admed=off&what=1","Adm Edit <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";

	sc_button( "$RFS_SITE_URL/admin/adm.php?textbuttons=true","Text Buttons <font style='color: green; background-color: dark-green;'> ON </font>" );
	sc_button( "$RFS_SITE_URL/admin/adm.php?textbuttons=false","Text Buttons <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";

	sc_button( "$RFS_SITE_URL/admin/adm.php?admin_show_top=hide","Hide banner" );
	sc_button( "$RFS_SITE_URL/admin/adm.php?admin_show_top=show","Show banner" );

	echo "</td></tr></table>";

    //echo "<hr>";

    admin_menu_built_in();

	echo "</div>";
	
	finishadminpage();
}
function admin_menu_built_in() { eval(scg());
    
        $arr=get_defined_functions();
        foreach( $arr['user'] as $k=>$v ) {
            if( stristr( $v,"adm_action_" ) ) {
                if( !stristr( $v,"_lib_") ) {
                    if( !stristr( $v,"_go" ) ) {
                        if( !stristr( $v,"_f_" ) ) {
                            $x=str_replace( "adm_action_","",$v );
							
							$lx=$x;
							
                            if(!empty($x)) {
								
								$target="";
								if(stristr($x,"_out")) {
									$target=" target=\"_blank\" ";
									$x=str_replace("_out","",$x);
								}
							
								
								echo "<div style='	float:left; border: 1px solid #000000; margin: 5px; padding:5px 10px; background:#555535; border-radius:12px;' > ";
                                echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=$lx\" $target>";
								
								$imglnk="<img src='$RFS_SITE_URL/admin/images/";
                                if( file_exists( "$RFS_SITE_PATH/admin/images/$x.png" )) $imglnk.="$x.png'";
								if( file_exists( "$RFS_SITE_PATH/admin/images/$x.gif" )) $imglnk.="$x.gif'";
								if( file_exists( "$RFS_SITE_PATH/admin/images/$x.jpg" )) $imglnk.="$x.jpg'";

                                $imglnk.=" width=64 height=64 border='0' align=center>";
								echo $imglnk;
								
								echo "</a><br>";
								echo "<a style='color: #cFcF00;' href='$RFS_SITE_URL/admin/adm.php?action=$lx'>";
								echo ucwords(str_replace("_"," ",$x));
								echo "</a>";
								echo "</div>";
							}
                    }
                }
            }
        }
    }


	$cres=sc_query( "select * from categories order by name asc" );
	$ccount=mysql_num_rows( $cres );
	for( $ci=0; $ci<$ccount; $ci++ ) {
        $cc=mysql_fetch_object( $cres );
        $res=sc_query( "select * from admin_menu where category = '$cc->name' order by name asc" );
        $count=mysql_num_rows( $res );
        if( $count ) {
            for( $i=0; $i<$count; $i++ ) {
            $icon=mysql_fetch_object( $res );

                echo "<div style='	float:left;
										border: 1px solid #000000;
										margin: 5px;
										padding:5px 10px;
										background:#357535;
										border-radius:12px; ' > ";
                
					echo "<a href=\"";
                $icon->url=str_replace( ";","%3b",$icon->url );
                rfs_echo( $icon->url );
                echo "\" target=\"$icon->target\">";

						if(!file_exists("$RFS_SITE_PATH/$icon->icon"))
							$icon->icon="images/icons/exclamation.png";
						echo "<img src=\"$RFS_SITE_URL/include/button.php?im=$RFS_SITE_PATH/$icon->icon&t=$icon->name&w=64&h=64&y=20\" border='0'></a><br>";
				echo "<a href=\""; 
                $icon->url=str_replace( ";","%3b",$icon->url );
                rfs_echo( $icon->url );
                echo "\" target=\"$icon->target\" style='color: #cFcF00;'>";
						echo ucwords(str_replace("_"," ",$icon->name));
						echo "</a>";

                if( $_SESSION['admed']=="on" ) {

                        sc_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_edit_entry&id=$icon->id","Edit" );
                        sc_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_edit_del&id=$icon->id","Delete" );
                        sc_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_change_icon&id=$icon->id","Change Icon" );
                        sc_optionizer( "$RFS_SITE_URL/admin/adm.php",
                                       "action=f_admin_change_category".$RFS_SITE_DELIMITER.
                                       "id=$icon->id",
                                       "categories",
                                       "name",
                                       0,
                                       $cc->name,
                                       1 );
                }
                echo "</div>";
            }
        }

    }


    $mods=sc_get_modules_array() ;

    foreach($mods as $mk=>$mv ) {

        $func_count=0;
        foreach( $arr['user'] as $k=>$v ) {
            if( stristr( $v,"_lib_$mv") ) {
                $x=str_replace( "adm_action_","",$v );
                if(!empty( $x )) $func_count++;
            }
        }

        if($func_count) {

            foreach( $arr['user'] as $k=>$v ) {
                if( stristr( $v,"_lib_$mv") ) {
                    $x=str_replace( "adm_action_","",$v );

                            if( !empty( $x ) ) {

									echo "<div style='	float:left;
															border: 1px solid #000000;
															margin: 5px;
															padding:5px 10px;
															background:#353575;
															border-radius:12px; ' > ";
															
									echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=$x\">";

									$px=str_replace("lib_" ,""   ,$x);
									$px=str_replace("$mv"."_" ,""   ,$px);
									$px=str_replace("_"    ," "  ,$px);
									$px=ucwords($px);
									$fn=strtolower($px);
									$fn=str_replace(" ","_",$fn);
									$img="$RFS_SITE_PATH/modules/$mv/images/$fn.png";

								
									$png="<img src=\"$RFS_SITE_URL/include/button.php?im=$img&t=$px&w=64&h=64&y=20\" border='0' ></a> ";
									if( !file_exists( $img ) ) {
										$png="<img src=\"$RFS_SITE_URL/include/button.php?im=$RFS_SITE_PATH/images/icons/exclamation.png&t=$px&w=64&h=64&y=20\" border='0' alt='$fn - $img' text='$fn - $img'></a> ";

								
								}

								echo $png;		
                            echo "</a><br>";
							
                            echo "<a style='color: #cFcF00;' href='$RFS_SITE_URL/admin/adm.php?action=$x'>$px</a>";

                            echo "</div>";
                        }
                    }
                }
                
        }
    }
	
	echo "<div style='clear: left; '>&nbsp;</div>";	
}
function finishadminpage() {
	eval( scg() );

    $arr=get_defined_functions();

	echo "<!-- FUNCTIONS AVAILABLE... JUST LOOK ";
	foreach( $arr['user'] as $k=>$v ) { echo "$v"; }
	echo " -->";


	include( "footer.php" );
	exit();
}
?>
