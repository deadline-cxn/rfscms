<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
$title="Administration";
/////////////////////////////////////////////////////////////////////////////////////////
include("lib.adm.php");
chdir( "../" );
include("include/lib.all.php");
/////////////////////////////////////////////////////////////////////////////////////////
// ACCESS CHECK
if(!lib_access_check("admin","access")) {
	echo lib_string_convert_smiles("<table border=0 width=300><tr><td class=warning><center>^X<br>You can not use admin</td></tr></table>\n");
	echo "<a href=\"$RFS_SITE_URL\">Back to $RFS_SITE_URL</a>";
	lib_log_add_entry( "*****> $data->name tried to access the admin area!" );
	include("footer.php");
	exit();
}
include( "lilheader.php" );
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM CHECK FOR UPDATES
function adm_action_update() {
    eval(lib_rfs_get_globals());
	set_time_limit(0);
    echo "<div class=forum_box>";
    echo "<pre>";
    echo "Update RFSCMS\n";
    echo "Local git repo:";
    $git=false;
    if(file_exists("$RFS_SITE_PATH/.git")) {
        echo " ---> true\n";
        $git=true;
    }
    else {
        echo " ---> false\n";
    }
    echo "\$RFS_SITE_FORCE_ZIP_UPDATE check:";
    if(lib_rfs_bool_true($RFS_SITE_FORCE_ZIP_UPDATE)) {
        echo " ---> true\n";
        $git=false;
    }
    else {
        echo " ---> false\n";
    }
    echo "Current folder:";
    system("pwd");
    if($git) {
        echo "Pulling remote git repo...\n";
        lib_rfs_flush_buffers();
        system("git stash save --keep-index");
        lib_rfs_flush_buffers();
        $f="https://github.com/sethcoder/rfscms.git";
        $x=system("git pull $f");
        lib_rfs_flush_buffers();
        lib_log_add_entry("Update: $x");
        echo "$f\n";
    }
    else {
        echo "Getting latest zip...\n";
		mkdir("$RFS_SITE_PATH/admin/update");
		chdir("$RFS_SITE_PATH/admin/update");
		lib_rfs_flush_buffers();
        $f="http://github.com/sethcoder/rfscms/archive/master.zip";
		echo system("wget $f");
		$c="unzip master.zip";
		echo system($c);
    }
    echo "</pre>";
    echo "</div>";
    include("footer.php");
    exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM BAN MANAGEMENT


/////////////////// BAN BY IP
function adm_action_f_banip() {
	eval(lib_rfs_get_globals());
	lib_domain_ban_ip($ip);
	lib_forms_info("$ip banned","white","red");
	adm_action_ban_management();
}
function adm_action_f_unbanip() { 
	eval(lib_rfs_get_globals());
	echo "<h1>UnBan IP Address</h1>";
	finishadminpage();					  
}
/////////////////// BAN BY REF
function adm_action_f_banref() {
	eval(lib_rfs_get_globals());
	lib_domain_ban_referrer($ip);
	lib_forms_info("$refer banned","white","red");
	adm_action_ban_management();
}
function adm_action_f_unbanref() {
	eval(lib_rfs_get_globals());
	echo "<h1>UnBan Referrer</h1>";
	finishadminpage();
}

/////////////////// BAN BY DOMAIN
function adm_action_f_bandomain() {
	eval(lib_rfs_get_globals());
	echo "<h1>Ban Domain</h1>";
	echo "<hr>$domain<hr>";
	lib_forms_confirm("Are you sure you want to ban this domain?",
					  "$RFS_SITE_URL/admin/adm.php?action=f_bandomain_go",
					  "domain=$domain");
	finishadminpage();
}
function adm_action_f_unbandomain() {
	eval(lib_rfs_get_globals());
	echo "<h1>UnBan Domain</h1>";
	echo "UnBanning domain $domain";
	lib_domain_unban_domain($domain);
	finishadminpage();					  
}
function adm_action_f_bandomain_go() {
	eval(lib_rfs_get_globals());
	echo "<h1>Ban Domain</h1>";
	echo "Banning domain $domain";
	lib_domain_ban_domain($domain);
	lib_log_add_entry("$data->name banned domain: $domain");
	echo "<hr>";
	adm_action_ban_management();
}
function adm_action_f_edit_banned_go() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Ban updated</h3>";
	lib_mysql_update_database( "banned","id",$id,1);
	adm_action_ban_management();
}
function adm_action_f_edit_banned() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from `banned` where `id`='$id'" );
	$banned=$res->fetch_object();
	echo "<h3>Editing ban [$banned->id]</h3>";
	lib_forms_build( "$RFS_SITE_URL/admin/adm.php","action=f_edit_banned_go".$RFS_SITE_DELIMITER."id=$id","banned","select * from `banned` where `id`='$id'","","id".$RFS_SITE_DELIMITER,"omit","",60,"update" );
	include("footer.php");
	exit();
}
function adm_action_f_del_banned_go() {
	eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from `banned` where `id`='$id'" );
	$banned=$res->fetch_object();
	if( $yes=="Yes" ) {
		echo "$banned->id removed from database";
		lib_mysql_query( "delete from `banned` where `id`='$id'" );
	}
	adm_action_ban_management();
}
function adm_action_f_del_banned() {
	eval( lib_rfs_get_globals() );
	echo lib_string_convert_smiles( "<p class=warning>^X<br>WARNING!<BR></p>" );
	$res=lib_mysql_query( "select * from `banned` where `id`='$id'" );
	$banned=$res->fetch_object();
	lib_forms_confirm( "Delete $banned->id?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_del_banned_go".$RFS_SITE_DELIMITER."id=$id" );
	include("footer.php");
	exit();
}
function adm_action_f_add_ban_manual_go() {
	$name	= addslashes($_REQUEST['name']);
	$domain	= addslashes($_REQUEST['domain']);
	$link	= addslashes($_REQUEST['link']);
	$ip		= addslashes($_REQUEST['ip']);	
	echo "<h1>Ban Management</h1><hr>";	
	lib_mysql_query("insert into banned (`name`,`domain`,`link`,`ip`) values ('$name','$domain','$link','$ip');");
	echo "<h2>Added</h2>";
	adm_action_ban_management();
}
function adm_action_f_add_ban_manual() {
	eval(lib_rfs_get_globals());
	echo "<h1>Ban Management</h1><hr><h2>Add a new BAN</h2>";
	lib_forms_build("$RFS_SITE_URL/admin/adm.php",
					"action=f_add_ban_manual_go",
					"banned",
					"",
					"",
					"id",
					"omit",
					"",
					60,
					"Add New BAN" );	
}

function adm_action_ban_management(){
	eval( lib_rfs_get_globals() );
	echo "<h1>Ban Management</h1><hr>";

	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_add_ban_manual","Add new BAN");

	echo "<hr>";
	lib_mysql_dump_table("banned,id,domain,link,ip", "showform".$RFS_SITE_DELIMITER."f_", "id","");
	finishadminpage();
/*
about	
access	
access_methods	
admin_menu	
ads_skyscrapers	
anyterm	
arrangement	
banned	
categories	
colors	
comics	
comics_page_templates	
comics_pages	
comments	
contentid	
counters	
course_component	
course_component_type	
courses	
courses_component	
criteria	
db_queries	
delp_last_searches	
file_duplicates	
files	
forum_list	
forum_posts	
link_bin	
meme	
menu_top	
network_devices	
news	
objectives	
pictures	
pmsg	
pod_completion	
pods	
projects	
resource_types	
rfsauth	
rfscms_gns3	
rfsm_bullet_category	
rfsm_bullet_log	
rfsm_fitness	
rfsm_political_game	
rss_feeds	
script_group_types	
script_groups	
scripts	
searches	
site_vars	
slogans	
smilies	
snippets	
tags	
todo_list	
todo_list_status	
todo_list_task	
todo_list_type	
topmenu	
topology	
transactions	
tripcodes	
tutorial_categories	
tutorials	
useronline	
users	
videos	
wab_calc	
wab_engine	
wab_showusers	
wab_tgk	
wiki	
 */
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_POLICY FUNCTIONS

function adm_action_policy_manager() {
	
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_AUTH FUNCTIONS
/* REPLACE ADM_AUTH WITH ADM_POLICIES 

function adm_action_auth_config() {
    eval(lib_rfs_get_globals());
	echo "<h1>Authentication Configuration</h1>";
	echo "<hr>";
	echo "<div> EBSR (Email-based Self-registration)";
	lib_ajax(	"Enabled",		"rfsauth",	"name", "EBSR", "enabled", 		1, "checkbox", "admin", "access", "");
	// lib_ajax(	"EBSR Value,180",	"rfsauth", "name", "EBSR", "value", 		60, "", 		"admin", "access", "");
	echo "</div>";
	echo "<hr>";
	echo "<div> Facebook";
	lib_ajax(	"Enabled",				"rfsauth",	"name", "FACEBOOK", "enabled", 		1, "checkbox", "admin", "access", "");
	lib_ajax(	"Facebook  APP  ID,180",	"rfsauth", "name", "FACEBOOK", "value", 		60, "", 		"admin", "access", "");						
	lib_ajax(	"Facebook SECRET ID,180","rfsauth", "name", "FACEBOOK", "value2", 		60, "", 		"admin", "access", "");						
	echo "</div>";
	echo "<hr>";
	echo "<div> OpenID";
	lib_ajax(	"Enabled",		"rfsauth",	"name", "OPENID", "enabled", 		1, "checkbox", "admin", "access", "");
	lib_ajax(	"OpenID ID,180",	"rfsauth", "name", "OPENID", "value", 		60, "", 		"admin", "access", "");						
	echo "</div>";
}
 */
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ARRANGE FUNCTIONS
function rfs_admin_module( $loc ) {
    eval( lib_rfs_get_globals() );
    $location=$loc;
	$r=lib_mysql_query( "select * from arrangement where location='$location' order by sequence " );
	if($r) {
		echo "<center><h2>$location";
		echo " Panels</h2>";
        if(!$r->num_rows) echo " ( NO PANELS IN THIS AREA! ) <BR> ";
        else
		for($i=0; $i<$r->num_rows; $i++) {
			echo "<div class=news_article>";
			$ar=$r->fetch_object();
			
			echo "<h1>";
			echo ucwords( str_replace("_"," ","$ar->panel") );
			echo "</h1>";						
			
			echo "<table border=0 cellspacing=0><tr>";
			
			echo "<td>";
            echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_delete&location=$location&arid=$ar->id' title='Delete'>";
			echo "<img src='$RFS_SITE_URL/images/icons/circle-delete.png' border='0' width=24 height=24>";
            echo "</a>";
			
			if( $ar->sequence > 1 ) {
				echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_move_up&arid=$ar->id' title='Move Up'>";
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-up.png width=24 height=24 border=0> ";
				echo "</a>";
			}
			
			if( $ar->sequence < $n ) {
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-down.png width=24 height=24 border=0> ";
			}
			
			if( $location!="left" ) {
				echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_move_left&arid=$ar->id' title='Move Left'>";
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-left.png width=24 height=24 border=0> ";
				echo "</a>";
			}
			if( $location!="right" ) {
				echo "<a href='$RFS_SITE_URL/admin/adm.php?action=f_arrange_move_right&arid=$ar->id' title='Move Right'>";
				echo " <img src=$RFS_SITE_URL/images/icons/arrow-right.png width=24 height=24 border=0> ";
				echo "</a>";
			}

			echo "</td>";
			
            echo "<td>";
			
			if(empty($ar->type)) $ar->type="results";
			lib_forms_optionize($phpself,"action=f_module_chg_type".$RFS_SITE_DELIMITER."id=$ar->id","panel_types","name","0",$ar->type,"1");
			
            // echo "$ar->id $ar->location $ar->panel $ar->num $ar->sequence $ar->type $ar->tableref $ar->access $ar->page";
			// id name table key other
            
            echo "</td>";
			
			
			
            if($ar->type=="results") {
                echo "<td>";
				echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>
    			        <input type=hidden name=action value=f_module_chg_num>
                        <input type=hidden name=id value='$ar->id'>
                        <input name=num size=1 value=$ar->num  onblur='this.form.submit()'>
    				</form> </td>";                    
            }
			
			if($ar->type=="static") {				
				echo "<td>";
				// echo "Type: static ";
				if(empty($ar->page)) {
					echo "(No page defined)<br>";
					echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>";
					echo "<input type=hidden name=action value=f_module_add_static>";
					echo "<input type=hidden name=arid value=$ar->id>";
					echo "Create new page: <input name=staticpage><br>";
					echo "<input type=submit>";
					echo "</form>";
				}
				else {
					lib_forms_optionize($phpself,"action=f_module_chg_static".$RFS_SITE_DELIMITER."id=$ar->id","static_html","name","",$ar->page,"1");

					
				}
				echo " </td>";
			}
			
			
			echo "	</td>";
			
			echo "</tr></table>";
			
			
			echo "<table border=0 cellspacing=0><tr>";
            

			
			if($ar->type=="static") {				
				echo "<td>";
				// echo "Type: static ";
				if(empty($ar->page)) {

				}
				else {
					
					
					$str=lib_mysql_query("select distinct * from static_html where name='$ar->page'");
					$st=$str->fetch_object();
					echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>";
					echo "<input type=hidden name=action value=f_module_edit_static_go>";
					echo "<input type=hidden name=arid value=\"$ar->id\">";
					echo "<input type=hidden name=staticpage value=\"$ar->page\">";
					echo "<textarea cols=50 rows=10 name=\"statichtml\">$st->html</textarea><br>";
					echo "<input type=submit></form>";					
					
					echo "Preview:<br>";
					$st->html=str_replace("&gt;",">",$st->html);
					$st->html=str_replace("&lt;","<",$st->html);
					echo lib_rfs_echo($st->html);
					
				}
				echo " </td>";
			}
                
                
            echo "</tr></table>";
			echo "</div>";
		}
		echo "<p>&nbsp;</p>";
	}
    
    
    
	echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>";
	echo "<input type=hidden name=action value=f_module_add>";
	
	echo "<input type=hidden name=location value=$location>";
    
    echo "<select name=module onchange='this.form.submit();'>";
    
	echo "<option>Add panel to this area";
	$arr=get_defined_functions();
	asort($arr['user']);
	foreach( $arr['user'] as $k=>$v ) {
		if( substr($v,0,8)=="m_panel_")  {
			$v=str_replace("m_panel_","",$v);
			echo "<option name='$v' value='$v'>";
			echo ucwords(str_replace( "_"," ",$v ) );
		}
	}
	echo "</select>";
	echo "</form>";
}

function adm_action_f_module_edit_static_go() {
	eval(lib_rfs_get_globals());
	echo "<h1>Edit static page $staticpage</h1>";
	echo "Entered html:<br>";
	echo lib_rfs_echo(nl2br($statichtml));
	echo "<br><textarea>$statichtml</textarea>";
	$statichtml=addslashes($statichtml);
	lib_mysql_query("update `static_html` set `html`='$statichtml' where name='$staticpage'");
	lib_mysql_query("update arrangement set `page`='$staticpage' where id='$arid'");
	adm_action_arrange();
}
function adm_action_f_module_add_static_go() {
	eval(lib_rfs_get_globals());
	echo "<h1>Add new static page $staticpage</h1>";
	echo "Add $staticpage to arrangement ($arid)<br>";
	echo lib_rfs_echo(nl2br($statichtml));
	echo "<br><textarea>$statichtml</textarea>";
	$statichtml=addslashes($statichtml);
	lib_mysql_query("insert into `static_html` (`name`,`html`) values('$staticpage','$statichtml')");
	lib_mysql_query("update arrangement set `page`='$staticpage' where id='$arid'");
	adm_action_arrange();
}
function adm_action_f_module_add_static() {
	eval(lib_rfs_get_globals());
	echo "<h1>Add new static page $staticpage</h1>";
	echo "Add $staticpage to arrangement ($arid)<br>";
	echo "Enter static html:<br>";
	echo "<form action='$RFS_SITE_URL/admin/adm.php' method='post'>";
	echo "<textarea name=statichtml></textarea>";
	echo "<input type=hidden name=action value=f_module_add_static_go>";
	echo "<input type=hidden name=arid value=$arid>";
	echo "<input type=hidden name=staticpage value=\"$staticpage\">";
	echo "<input type=submit>";
	echo "</form>";
}
function adm_action_f_arrange_move_up() {
    eval(lib_rfs_get_globals());
	$ar=lib_mysql_fetch_one_object("select * from arrangement where `id`='$arid'");	
	//echo $ar->panel."<br>";
	//echo $ar->location."<br>";
	//echo $ar->num."<br>";
	//echo $ar->sequence."<br>";
	$arrange=array();
	$r=lib_mysql_query("select * from arrangement where `location`='$ar->location'");
	while($tar=$r->fetch_object()) $arrange[$tar->id]=$tar->sequence;
	$ar->sequence--;
	foreach($arrange as $k => $v) {
		if($v==$ar->sequence) $arrange[$k]=$v+1;
	}
	$arrange[$ar->id]=$ar->sequence;
	foreach($arrange as $k => $v) {
		//$x=lib_mysql_fetch_one_object("select * from arrangement where `id`='$k'");
		lib_mysql_query("update arrangement set `sequence`='".$arrange[$k]."' where `id`='$k'");
	}
	// print_r($arrange);
	adm_action_arrange();
}
function adm_action_f_arrange_delete_go() {
    eval(lib_rfs_get_globals());
    lib_mysql_query("delete from arrangement where `id`='$id'");
    adm_action_arrange();
}
function adm_action_f_arrange_delete() {
    eval(lib_rfs_get_globals());
    $ar=lib_mysql_fetch_one_object("select * from arrangement where `location` = '$location' and `id`= '$arid' ");
    echo "Delete arrangement ($location: $ar->panel)<br>";
    lib_forms_confirm( "Delete $ar->panel from $location?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_arrange_delete_go".$RFS_SITE_DELIMITER."id=$ar->id" );
    adm_action_arrange();
}
function adm_action_f_module_add() {
    eval( lib_rfs_get_globals() );
	echo ".. $module... $location";
	
	$num=5;
	if(stristr($module,"static_html"))  { $type="static"; $num=""; }
	
	$r=lib_mysql_query("select max(sequence) as seq from arrangement where location = '$location'");
	$ars=$r->fetch_object();
	$nseq=$ars->seq+1;
	echo "$ars->seq $nseq  <br>";
	
	lib_mysql_query( "insert into arrangement  (`panel`,`location`,`num`,`type`,`sequence`)
	                                   values('$module','$location','$num', '$type','$nseq');" );
	adm_action_arrange();
}
function adm_action_f_module_chg_type() {
    eval( lib_rfs_get_globals() );
    //echo "$id... $name";
    lib_mysql_query( "update `arrangement` set `type`='$name' where id='$id'" );
	adm_action_arrange();
}
function adm_action_f_module_chg_num() {
    eval( lib_rfs_get_globals() );
	lib_mysql_query( "update arrangement set `num` ='$num' where id='$id'" );
	adm_action_arrange();
}
function adm_action_arrange() {
    eval( lib_rfs_get_globals() );
    $location="";
	
	
	lib_mysql_query(" CREATE TABLE IF NOT EXISTS `arrangement` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`location` text NOT NULL,
				`panel` text NOT NULL,
				`num` int(11) NOT NULL,
				`sequence` int(11) NOT NULL,
				PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ; ");

	echo "<div class=forum_box>";

	echo "<table border=0 width=100%><tr>"; // TOP START
	echo "<td valign=top>";
	rfs_admin_module("left");
	echo "</td><td valign=top width=80%>"; // MIDDLE START
    rfs_admin_module("middle");
    echo "</td><td valign=top>"; // RIGHT SIDE START
    rfs_admin_module("right");
    echo "</td> </tr> </table>"; // TOP END
    echo "<table border=0 width=100%><tr><td>"; // BOTTOM START
    rfs_admin_module("bottom");
    echo "</td></tr></table>"; // BOTTOM END
	echo "</div>";
	include( "footer.php" );
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM ACCESS GROUPS FUNCTIONS
function adm_action_f_access_group_add() {
    eval(lib_rfs_get_globals());
	echo " Adding new access group named [$axnm] <br>";
	lib_mysql_query(" insert into `access` (`name`) VALUES ('$axnm'); ");
	adm_action_f_access_group_edit();
}
function adm_action_f_access_group_edit_go() {
    eval(lib_rfs_get_globals());
	lib_mysql_query("delete from `access` where name='$axnm'");
	$r=lib_mysql_query("select * from `access_methods`");
	while($am=$r->fetch_object()) {
		if($_POST["$am->page"."_$am->paction"]=="on") {
			lib_mysql_query("insert into `access` (`name`,`page`,`paction`)
							VALUES('$axnm','$am->page','$am->paction')");
		}
	}
	adm_action_access_groups();
}
function adm_action_f_access_group_edit() {
    eval(lib_rfs_get_globals()); 
	echo "<h1>Edit Access Group</h1>";
	echo "<hr>";
	echo "<h2>$axnm</h2>";
	echo "<hr>";
	
	echo "<script> $(document).ready(function () {    $('#selectall').click(function () { $('.selectedId').prop('checked', this.checked); }); $('.selectedId').change(function () { var check = ($('.selectedId').filter(\":checked\").length == $('.selectedId').length); $('#selectall').prop(\"checked\", check);}); }); </script>";
	echo "<input type=\"checkbox\" id=\"selectall\">Select all</input>";
	echo "<div class=\"forum_box\">";
	echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"f_access_group_edit_go\">";
	echo "<input type=\"hidden\" name=\"axnm\" value=\"$axnm\">";
	
	$r=lib_mysql_query("select * from `access_methods` order by `page`,`paction`");
	
	while($am=$r->fetch_object()) {
		$q="select * from `access` where name='$axnm' and page='$am->page' and `paction`='$am->paction'";
		
		$wwrw=lib_mysql_fetch_one_object($q);
		
		$checked="";
		if($wwrw->name==$axnm) {
			
			$checked="checked";
			
		}
		
		echo "<div style=\"float: left; width: 230px;\">";
		echo "<input  type=\"checkbox\" class=\"selectedId\" 
			name=\"$am->page"."_$am->paction\" $checked >";
		echo " $am->page -> $am->paction";
		echo "</div>";
		
	}
	
	echo "<div style=\"clear: left;\"></div>";
	echo "</div>";
	echo "<input type=\"submit\" value=\"Update\">";
	echo "</form>";
	include( "footer.php" );
	exit();
}
function adm_action_f_access_group_add_user() {
    eval(lib_rfs_get_globals());
	$usr=lib_users_get_data($name);
	$usr->access_groups.=",$axnm";	
	lib_mysql_query("update `users` set access_groups ='$usr->access_groups' where id='$usr->id'");
	adm_action_access_groups();
}
function adm_action_f_access_group_del_user() {
    eval(lib_rfs_get_globals());
	echo $user;
	echo "<br>";
	$usr=lib_users_get_data($user);
	$agx=explode(",",$usr->access_groups);
	for($i=0;$i<count($agx);$i++){
		if($agx[$i]!=$axnm) {
			$outag=$outag.$agx[$i].",";
		}
	}
	$outag=rtrim($outag,",");
	lib_mysql_query("update `users` set access_groups='$outag' where name='$user'");
	adm_action_access_groups();	
}
function adm_action_f_access_group_delete() {
    eval(lib_rfs_get_globals());
	lib_forms_confirm( "Delete $axnm?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_access_group_delete_go".$RFS_SITE_DELIMITER.
					  "axnm=$axnm" );
	adm_action_access_groups();
}
function adm_action_f_access_group_delete_go() {
    eval(lib_rfs_get_globals());
	echo "DELETE $axnm access group... <BR>";
	lib_mysql_query("delete from `access` where name='$axnm'");
	adm_action_access_groups();
}
function adm_action_access_groups() {
    eval(lib_rfs_get_globals());    
	echo "<h1>Modify Access Groups</h1>";
	echo "<hr>";
	echo "<h2>Create a new access group</h2>";
	lib_div("ADD ACCESS GROUP FORM START");
	echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"f_access_group_add\">\n";
	echo "<input name=\"axnm\">\n";
	echo "<input type=\"submit\" value=\"Add\">\n";
	echo "</form>\n";
	lib_div("ADD ACCESS GROUP FORM END");
	$r=lib_mysql_query("select distinct name from access");
	while($a=$r->fetch_object()) {
		echo "<div class=\"forum_box\" style=\"float:left; width:300px;\">";
		echo "<h2>$a->name</h2>";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_delete&axnm=$a->name\">delete</a>] ";
		echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_edit&axnm=$a->name\">edit</a>]<br>";
		echo "<p>Members: </p>";
		echo "<p>";		
		$usrs=lib_mysql_query("select * from `users`");
		while($usr=$usrs->fetch_object()) {			
			$agrps=explode(",",$usr->access_groups);
			for($k=0;$k<count($agrps);$k++) {
				if($a->name==$agrps[$k]) {					
					echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_access_group_del_user&axnm=$a->name&user=$usr->name\">remove</a>] ";
					echo " $usr->name <br>";
                }
			}
		}
		lib_forms_optionize("$RFS_SITE_URL/admin/adm.php","action=f_access_group_add_user".$RFS_SITE_DELIMITER."axnm=$a->name".$RFS_SITE_DELIMITER."omit=access_groups:like '%$a->name%',name:anonymous","users","name",0,"Add a user to this group",1 );
		echo "</p>";
		echo "</div>";
	}
	include( "footer.php" );
	exit();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_PHPMYADMIN
function adm_action_phpmyadmin_out() { 
    eval(lib_rfs_get_globals());
    lib_domain_gotopage("$RFS_SITE_URL/3rdparty/phpmyadmin/");
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_FORM BUILDER FUNCTIONS
/*function adm_action_form_builder() { eval(lib_rfs_get_globals());
	echo"<p>Form Builder</p>";
	include( "footer.php" );
	exit();
}*/
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_NEW PAGE FUNCTIONS
/*function adm_action_new_page() { eval(lib_rfs_get_globals());
	echo"<p>Create a new page.</p>";
	lib_forms_build_quick( "action=new_page_go".$RFS_SITE_DELIMITER."SHOW_TEXT_name=name.php","Create new page");
	include( "footer.php" );
	exit();
}
function adm_action_new_page_go() { eval(lib_rfs_get_globals());
	if(!file_exists($_GLOBALS['name'])){
	copy("_template.php",$_GLOBALS['name']);
	}
	echo "<p> New file ".$_GLOBALS['name']." created. </p>";
}*/
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_EMAIL
function adm_action_email() {
    eval(lib_rfs_get_globals());
	echo"<p>Send an email</p>";
	lib_forms_build_quick( 	"action=email_go".$RFS_SITE_DELIMITER.
				"SHOW_TEXT_address=address".$RFS_SITE_DELIMITER.
				"SHOW_TEXT_subject=subject".$RFS_SITE_DELIMITER.
				"SHOW_CODEAREA_300#600#message=message".$RFS_SITE_DELIMITER,
				"Email");
	include("footer.php");
	exit();
}
function adm_action_email_go() {
    eval(lib_rfs_get_globals());
    echo "Sending message:<br>";
    echo "TO:$address<br>SUBJECT:$subject<br>MESSAGE:<br>$message<br>";
    mailgo($address,$message,$subject);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_DATABASE
/////////////////////////////////////////////////////////////////////////////////////////
function adm_db_query( $x ) {
    eval( lib_rfs_get_globals() );
	$y=urlencode($x);
	echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_rm_db_query&query=$y\" target=_top>Delete</a>]";	
	echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=db_query&query=$y\" target=_top>$x</a><br>";
}
function adm_action_f_rm_db_query() {
    eval(lib_rfs_get_globals());
	$q="delete from `db_queries` where `query` = \"$query\";";
	lib_mysql_query($q);
	$query="";
	$_GLOBALS['query']="";
	$_POST['query']="";
	$_GET['query']="";
   echo "<h3>Select a previously entered query</h3>";
   echo "<iframe id=\"QU\" width=600 height=400 class='iframez' frameborder=0
			src=\"$RFS_SITE_URL/admin/adm.php?action=lib_ajax_callback_query_list\"
			style=\"float:left;\"></iframe>";
	echo "<div style=\"float:left;\">";
	echo "<h3>Enter a new query</h3>";
	lib_mysql_database_query_form( "$RFS_SITE_URL/admin/adm.php","db_query","$query" );
	echo "</div><div style=\"clear:both;\">";
	finishadminpage();
}
function adm_action_db_query() {
    eval(lib_rfs_get_globals());
	echo "<h1>Database Query</h1><hr>";
	$r=lib_mysql_query("select * from db_queries");
	while($q=$r->fetch_object()) {
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		$q->query=rtrim($q->query,"\r");
		$q->query=rtrim($q->query,"\n");
		lib_mysql_query("update db_queries set query= '$q->query' where `id`='$q->id'");
	}
	
	echo "<div class='forum_message' style='float:left; height:230px;'>";
   echo "<h3>Select a previously entered query</h3>";   
   
   echo "<iframe id=\"QU\" width=600  class='iframez' frameborder=0
			src=\"$RFS_SITE_URL/admin/adm.php?action=lib_ajax_callback_query_list\"
			style=\"float:left;\"></iframe>";
	
	echo "</div>";
	echo "<div class='forum_message' style=\"float:left; height:230px;\">";
	
	echo "<h3>Enter a new query</h3>";
	lib_mysql_database_query_form( "$RFS_SITE_URL/admin/adm.php","db_query","$query" );
	echo "</div><div style=\"clear:both;\">";
	if( !empty( $query ) ) {	
		// echo " DING [$query] <BR>";	
		lib_mysql_query( "insert into `db_queries` (`id`, `query`) VALUES ('',\"$query\" ) " );
		echo "<div class=forum_box>";
		echo $query;
		// echo "<table cellspacing=0 cellpadding=0 border=0><tr><td class=contenttd>";
		lib_mysql_database_query( $query, 1 );
		// echo "</td></tr></table>";
		echo "</div>";
	}
    finishadminpage();
}
function adm_action_database_backup() {
    eval(lib_rfs_get_globals());
	echo "<div class=forum_box>";
	echo "<h1>Database Backup</h1><hr>";
	$sn=str_replace("http://","",$RFS_SITE_URL);
	$sn=str_replace("/","",$sn);
	
	echo (lib_mysql_backup_database($RFS_SITE_PATH."/files/.backups/$sn"));
		
	echo (lib_mysql_backup_table("addon_database",$RFS_SITE_PATH."/files/addon_database.sql"));
	
	echo "</div>";
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
	eval( lib_rfs_get_globals() );
	$eval=stripslashes( $eval );
	ob_start( "eval_callback" );
	eval( "$eval" );
	ob_end_flush();
	finishadminpage();
}
function adm_action_eval_form() {
	eval( lib_rfs_get_globals() );
	echo "<h1>Enter PHP code to eval:</h1><hr>";
	lib_forms_build( lib_domain_phpself(),
	       "action=eval_form_go".$RFS_SITE_DELIMITER.
	       "id=$id".$RFS_SITE_DELIMITER.
	       "SHOW_TEXTAREA_16#70#eval=enter code here",
	       "","","","","","",50,"Eval Code" );
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_THEMES
function adm_action_f_theme_edit_css() {
    eval(lib_rfs_get_globals());
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
function adm_action_f_theme_edit_php() {
    eval(lib_rfs_get_globals());
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
function adm_action_f_theme_edit_delete_go() {
    eval(lib_rfs_get_globals());
	$file="$RFS_SITE_PATH/themes/$thm/$dfile";
	echo " DELETING $file...<br>";
	system("$RFS_SITE_SUDO_CMD rm $file");
	adm_action_f_theme_edit();
}
function adm_action_f_theme_edit_delete() {
    eval(lib_rfs_get_globals());
	$file="$RFS_SITE_PATH/themes/$thm/$dfile";
	lib_forms_confirm(	"Delete $file<br><br><img src=\"$RFS_SITE_URL/themes/$thm/$dfile\" width=500> ",
						"$RFS_SITE_URL/admin/adm.php",
						"action=f_theme_edit_delete_go".$RFS_SITE_DELIMITER.
						"dfile=$dfile".$RFS_SITE_DELIMITER.
						"thm=$thm"
						
						);
}
function adm_action_f_ajx_theme_edit_save_t_php() {
    eval(lib_rfs_get_globals());
	$thm=$_POST['thm'];
	$taval=$_POST['taval'];
	$taval=urldecode($taval);
	$taval=stripslashes($taval);
	$file="$RFS_SITE_PATH/themes/$thm/t.php";
	system("$RFS_SITE_SUDO_CMD mv $file $file.bak.".time());
	system("$RFS_SITE_SUDO_CMD touch $file");
	system("$RFS_SITE_SUDO_CMD chmod 777 $file");
	if(!file_put_contents($file,$taval))
		lib_forms_info("ERROR SAVING FILE, CHECK PERMISSIONS","WHITE","RED");
	else 
		lib_forms_info("FILE SAVED","WHITE","GREEN");
}
function adm_action_f_theme_edit_t_php() {
    eval(lib_rfs_get_globals()); 
	echo "<h3> Editing theme [$thm] </h3>";	
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_edit&thm=$thm","Cancel");
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
function adm_action_f_ajx_theme_edit_save_t_css() {
    eval(lib_rfs_get_globals());
	$thm=$_POST['thm'];
	$taval=$_POST['taval'];
	$taval=urldecode($taval);
	$taval=stripslashes($taval);
	$file="$RFS_SITE_PATH/themes/$thm/t.css";
	system("mv $file $file.bak.".time());
	system("touch $file");
	system("chmod 775 $file");
	if(!file_put_contents($file,$taval))
		lib_forms_info("ERROR SAVING FILE, CHECK PERMISSIONS","WHITE","RED");
	else 
		lib_forms_info("FILE SAVED","WHITE","GREEN");
}
function adm_action_f_theme_edit_t_css() {
    eval(lib_rfs_get_globals()); 
	echo "<h3> Editing theme [$thm] </h3>";	
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_edit&thm=$thm","Cancel");

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
function adm_action_f_theme_clone_go() {
    eval(lib_rfs_get_globals());
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
function adm_action_f_theme_clone() {
    eval(lib_rfs_get_globals());
	echo "<h1>Clone $thm theme</h1>";
	$sample="themes/$thm/t.sample.png";
	if(file_exists("$RFS_SITE_PATH/$sample")) {
		echo lib_images_thumb("$RFS_SITE_PATH/$sample",300,0,0);
	}
	lib_forms_build(	"$RFS_SITE_URL/admin/adm.php",
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
function adm_action_f_theme_edit() {
    eval(lib_rfs_get_globals());
	echo "<h1>Editing theme [$thm]</h1>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=theme","Themes list");
	echo "<hr>";	
	$folder="$RFS_SITE_PATH/themes/$thm";
	echo "Elements of $folder <br>";
	$d = opendir($folder);
	while(false!==($entry = readdir($d))) {
		if(($entry != '.') && ($entry != '..') && (!is_dir($dir.$entry)) ) {
			if($entry[0]=="t") {
				$ft=lib_file_getfiletype($entry);
				switch($ft) {
					case "css":
						if($entry=="t.css") {
							echo "<hr>";
							echo "<h1>$entry</h1>";
							echo "[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_theme_edit_t_css&thm=$thm&tcss=$entry\">edit this file</a>]<br>";
							lib_forms_css_file(	"$folder/$entry",
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
							lib_forms_php_file(		"$folder/$entry",
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
				$ft=lib_file_getfiletype($entry);				
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
function adm_action_f_theme_view_classes() {
    eval(lib_rfs_get_globals());
	$file="$RFS_SITE_PATH/tools/classes.out.txt";
	echo $file."<BR>";
	echo "<pre>";
	include($file);
	echo "</pre>";
	adm_action_theme();
}
function adm_action_f_theme_add_css_value_go() {
    eval(lib_rfs_get_globals());
	echo "<h1> $css </h1>";
	echo "<h1> $cssvalue</h1>";
	$thms=lib_themes_get_array();
	while(list($key,$thm)=each($thms)) {
		echo "<p>$thm</p>";
		$x=file_get_contents("$RFS_SITE_PATH/themes/$thm/t.css");
		if(stristr($x,$css)) {
			echo "<font style='color:white; background-color:green;'>EXISTS</font>";
		}
		else {
			echo "<font style='color:white; background-color:red;'>Adding [$css]</font>";
			file_put_contents("$RFS_SITE_PATH/themes/$thm/t.css", $css."\n".$cssvalue."\n", FILE_APPEND);
		}
	}
}
function adm_action_f_theme_add_css_value() {
    eval(lib_rfs_get_globals());
	lib_forms_build_quick(	"SHOW_TEXT_css".$RFS_SITE_DELIMITER.
			"SHOW_TEXTAREA_cssvalue".$RFS_SITE_DELIMITER.
			"action=f_theme_add_css_value_go","Add");
}
function adm_action_f_theme_css_checker() {
    eval(lib_rfs_get_globals());
	$file="$RFS_SITE_PATH/tools/classes.out.txt";
	$cssf=file_get_contents($file);
	$css=explode("\n",$cssf);
	foreach($css as $x => $y) {
		$y=".$y";
		echo "$y<br>";
		$css=$y;
		$cssvalue="{\n}\n";
		
		$thms=lib_themes_get_array();
		while(list($key,$thm)=each($thms)) {
			echo "<p>$thm</p>";
			$x=file_get_contents("$RFS_SITE_PATH/themes/$thm/t.css");
			if(stristr($x,$css)) {
				echo "<font style='color:white; background-color:green;'>EXISTS</font>";
			}
			else {
				echo "<font style='color:white; background-color:red;'>Adding [$css]</font>";
				file_put_contents("$RFS_SITE_PATH/themes/$thm/t.css", $css."\n".$cssvalue."\n", FILE_APPEND);
			}
		}
	}
	adm_action_theme();
}
function adm_action_theme() {
    eval(lib_rfs_get_globals());
	echo "<h1>Theme Editor</h1><hr>";	
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_view_classes","View CSS Classes");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_add_css_value","Write CSS value to all themes");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_theme_css_checker","Check themes for missing CSS");
	echo "<hr>";
	$thms=lib_themes_get_array();
	while(list($key,$thm)=each($thms)) {
		echo " <div  style=\"float: left; height: 150px; margin: 20px; padding: 10px;\">";

		echo ucwords("<h3> $thm </h3>");		
		$sample="themes/$thm/t.sample.png";
		
		if( (!file_exists("$RFS_SITE_PATH/$sample")) || 
			 $force_images=="1"	) {
			$cmd="$RFS_SITE_PATH/tools/bin/wkhtmltoimage --crop-h 800 --crop-w 1200 $RFS_SITE_URL?theme=$thm $RFS_SITE_PATH/$sample";
			system($cmd);
		}
		echo lib_images_thumb("$RFS_SITE_PATH/$sample",100,80,0);
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
function adm_action_f_addsitevar() {
    eval( lib_rfs_get_globals() );
	$name=strtolower( $name );
	$name=str_replace( " ","_",$name );
	$val=addslashes( $_REQUEST['val'] );
	lib_sitevars_assign($name,$val,$type,$desc);
	adm_action_edit_site_vars();
}
function adm_action_f_upsitevar() {
    eval( lib_rfs_get_globals() );
	$sv=lib_mysql_fetch_one_object("select * from site_vars where id=$svid");
	$x=strtoupper($sv->name);
	lib_forms_info("[\$RFS_SITE_$x] [$type] [$desc] [$val]","white","green");
	$val=addslashes( $_REQUEST['val'] );
	lib_sitevars_assign($x,$val,$type,$desc);
	adm_action_edit_site_vars();
}
function adm_action_f_delsitevar_go() {
    eval( lib_rfs_get_globals() );
	lib_mysql_query( "delete from `site_vars` where `id`='$svid'" );
	adm_action_edit_site_vars();
}
function adm_action_f_delsitevar() {
    eval(lib_rfs_get_globals());
	$site_var=lib_mysql_fetch_one_object("select * from site_vars where id='$svid'");
	lib_forms_confirm("<p>Delete \$RFS_SITE_$site_var->name ?</p>","$RFS_SITE_URL/admin/adm.php?action=f_delsitevar_go","id=$id");
	adm_action_edit_site_vars();
}
function adm_action_edit_site_vars() {
    eval( lib_rfs_get_globals() );
	echo "<div class=forum_box>";
	echo "<h1>Edit Site Variables</h1><hr>";
	
	echo "<table border=0>";
	echo "<tr><th>Variable</th><th>Type</th><th>Value</th><th></th><th></th></tr>";
	$res=lib_mysql_query("select * from site_vars order by name");
	while($site_var=$res->fetch_object()) {
		echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\" enctype=\"application/x-www-form-URLencoded\">";
		echo "<tr><td>";
		echo "<input type=hidden name=action value=\"f_upsitevar\">";
		echo "<input type=hidden name=\"svid\" value=\"$site_var->id\">";
		$site_var->name=strtoupper(stripslashes(($site_var->name)));
		echo "\$RFS_SITE_$site_var->name <br>$site_var->desc";
		echo "</td><td>";
		echo "<select name=\"type\" onchange=\"form.submit();\">";		
		if(empty($site_var->type)) $site_var->type="text";
		echo "<option>$site_var->type";		
		echo "<option>text";
		echo "<option>textarea";
		echo "<option>time";
		echo "<option>bool";
		echo "<option>file";
		echo "<option>theme";
		echo "<option>number";
		echo "<option>password";
		echo "</select>";		
		echo "</td><td>";		
		$site_var->value=stripslashes( $site_var->value );
		$site_var->value=str_replace("<","&lt;",$site_var->value);
		$site_var->value=str_replace(">","&gt;",$site_var->value);
		$site_var->value=str_replace('"',"&#34;", $site_var->value);
		switch($site_var->type) {
		case "bool":
			echo "<select name=val onchange=\"form.submit();\">";			
			if(lib_rfs_bool_true($site_var->value)) {
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
			$thms=lib_themes_get_array();
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
				echo "<font style='color:white; background-color:red;'><br>FILE DOES NOT EXIST";
			}
			break;
			
		case "textarea":
			echo "<textarea name=val cols=40 rows=10 onblur=\"form.submit();\">$site_var->value</textarea>";		
			break;
		default:
			
			echo "<input name=val size=40 value=\"$site_var->value\" onblur=\"form.submit();\">";
			break;
		}
		echo "</td>";
		
		echo "<td>";
		rfs_db_element_edit("","$RFS_SITE_URL/admin/adm.php","edit_site_vars","site_vars", $site_var->id);
		echo "</td></tr>";
		echo "</form>";
	}
	
	echo "</table>";
	
	echo "</div>";
	
	echo "<div class=\"forum_box\">";
	echo "<table>";
	echo "<tr><td>";
	echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=\"f_addsitevar\">";
	echo "<input type=hidden name=id value=\"$site_var->id\">";
	echo "\$RFS_SITE_<input name=name size=17 value=\"ADD NEW\"> ";
	echo "<select name=\"type\">";		
		echo "<option>text";
		echo "<option>textarea";
		echo "<option>time";
		echo "<option>bool";
		echo "<option>file";
		echo "<option>theme";
		echo "<option>number";
		echo "<option>password";
		echo "</select>";
	echo "<input name=val size=40 value=\"INITIAL VALUE\">";
	// echo "<br><textarea cols=100 name=desc>DESCRIPTION OF THIS VARIABLE</textarea>";
	echo "<input type=submit value=\"go\">";
	echo "</form>";
	echo "</td><td>";
	echo "</td></tr>";
	echo "</table>";	
	
	echo "<h2>All site vars</h2>";
	echo "<table border=0>";
	foreach($GLOBALS as $k => $v) {
		if(stristr($k,"RFS_SITE")) {
			
			$x=str_replace("RFS_SITE_","",$k);
			$x=strtolower($x);
			$rescheck=lib_mysql_query("select * from site_vars where name='$x'");
			$svarcheck=$rescheck->fetch_object();
			if(empty($svarcheck)) {
			
			echo "<tr>";
			$v=str_replace("<","&lt;",$v);
			$v=str_replace(">","&gt;",$v);
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_addsitevar_from_global&name=$k","Add to database");
			
			echo "</td>";
			echo "<td>";
			echo " $k ";
			echo "</td>";
			echo "<td>";
			echo "<textarea cols=100 rows=10>$v</textarea>";
			echo "</td>";
			echo "</tr>";
			
			}
		}
	}
	echo "</table>";
	
	
	echo "</div>";
	
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_MENU ADMIN
function adm_action_f_admin_menu_change_icon() {
    eval( lib_rfs_get_globals() );
	$_SESSION['select_image_path']="";
	lib_images_select( "images","admin/adm.php","", "admin_menu", $id, "icon" );
}
function adm_action_f_admin_menu_edit_del_go() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=$res->fetch_object();
	echo "<h3>Edit Admin Menu :: Delete $menuitem->name :: DELETED!</h3>";
	lib_mysql_query( "delete from admin_menu where `id`='$id'" );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_del() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=$res->fetch_object();
	echo "<h3>Edit Admin Menu</h3>";
	echo "<table class=warning><tr><td>";
	echo lib_string_convert_smiles( "Delete $menuitem->name ^X" );
	echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=f_admin_menu_edit_del_go>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=submit name=submit value=confirm></form>";
	echo "</td></tr></table>";
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_menu_admin_add_link() {
    eval(lib_rfs_get_globals());
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
function adm_action_f_admin_menu_edit_add() {
    eval( lib_rfs_get_globals() );
	echo "<h3>Edit Admin Menu :: Add $mname</h3>";
	if(empty($mname))		$mname		= addslashes( $_REQUEST['mname'] );
	if(empty($murl)) 		$murl		= addslashes( $_REQUEST['murl'] );
	if(empty($mtarget))	$mtarget	= addslashes( $_REQUEST['mtarget'] );
	if(empty($mcategory)) {
		$mcat=lib_mysql_fetch_one_object("select * from categories");
		$mcategory=$mcat->name;
	}		
	$q="INSERT INTO `admin_menu`	 (`category`,      `name`,    `icon`,    `url`,     `target`)
							    VALUES ('$mcategory',  '$mname',  '$micon',    '$murl',   '$mtarget') ;";
	echo $q."<BR>";
	lib_mysql_query( $q );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_change_category() {
    eval( lib_rfs_get_globals() );
	echo "id: $id<br>";
	echo "cat: $name<br>";
	lib_mysql_query( "update admin_menu set `category`='$name' where `id`='$id'" );
    if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_mod() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from admin_menu where `id`='$id'" );
	$menuitem=$res->fetch_object();
    if(empty($mname)) $mname=$name;
	echo "<h3>Edit Admin Menu :: Modify $menuitem->name = $mname</h3>";
	if( empty( $mname ) )       $mname=$menuitem->name;
	if( empty( $murl ) )        $murl =$menuitem->url;
	if( empty( $micon ) )       $micon=$menuitem->icon;
	if( empty( $mtarget ) )     $mtarget=$menuitem->target;
	if( empty( $mcategory ) )   $mcategory=$menuitem->category;
	echo $murl;
	lib_mysql_query( "update admin_menu set `name`='$mname' where `id`='$id'" );
	lib_mysql_query( "update admin_menu set `url`='$murl' where `id`='$id'" );
	lib_mysql_query( "update admin_menu set `icon`='$micon' where `id`='$id'" );
	lib_mysql_query( "update admin_menu set `target`='$mtarget' where `id`='$id'" );
	lib_mysql_query( "update admin_menu set `category`='$mcategory' where `id`='$id'" );
	if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_f_admin_menu_edit_entry_data($inid,$tdlc) {
    eval(lib_rfs_get_globals());
        $id=$inid;
        d_echo("EDITING ADMIN MENU ENTRY: $id");
        $menuitem=lib_mysql_fetch_one_object( "select * from admin_menu where id='$id'" );
        echo "<tr>";
		echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<input type=hidden name=action value=f_admin_menu_edit_del>";
		echo "<input type=hidden name=id value=$menuitem->id>";
		echo "<div class=redbutton><input type=submit name=submit value=delete></div>";
		echo "</form>";
		echo "</td>";
		echo "<form method=\"post\" enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\">";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<input type=hidden name=action value=f_admin_menu_edit_mod>";
		echo "<input type=hidden name=id value=$menuitem->id>";
		echo "<input size=20 type=text name=mname value=\"$menuitem->name\">";
		echo "</td>";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<Select name=mcategory>";
		if( !empty( $menuitem->category ) )
			echo "<option>$menuitem->category";
		$cres=lib_mysql_query( "select * from categories order by name" );
		for( $i3=0; $i3<$cres->num_rows; $i3++ ) {
			$c=$cres->fetch_object();
			echo "<option>$c->name";
		}
		echo "</select>";
		echo "</td>";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<input size=40 type=text name=murl value=\"$menuitem->url\">";
		echo "</td>";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<a href='adm.php?action=f_admin_menu_change_icon&id=$menuitem->id'>";
		echo "<img src=\"$RFS_SITE_URL/$menuitem->icon\" width=64 height=64 border='0'><br>Change</a>";
		echo "<input size=40 type=text name=micon value=\"$menuitem->icon\">";
		echo "</td>";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<input type=text name=mtarget value=\"$menuitem->target\">";
		echo "</td>";
		echo "<td class=rfs_project_table_$tdlc valign=bottom>";
		echo "<div class=menutop><input type=submit name=submit value=modify></div>";
		echo "</td>";
		echo "</form>";
		echo "</tr> ";
}
function adm_action_f_admin_menu_edit_entry() {
    eval(lib_rfs_get_globals());
	echo "<h3>Edit Admin Menu item $id</h3>";
	lib_forms_build(  "$RFS_SITE_URL/admin/adm.php",
            "action=f_admin_menu_edit_mod".$RFS_SITE_DELIMITER."id=$id",
            "admin_menu",
            "select * from admin_menu where `id`='$id'",
            "", "id", "omit", "", 60, "Modify" );
    if( $_SESSION['admed']=="on" ) adm_action_();
	else adm_action_admin_menu_edit();
}
function adm_action_admin_menu_edit() {
    eval( lib_rfs_get_globals() );
	echo "<h1>Edit Admin Menu</h1><hr>";
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
	$cres=lib_mysql_query( "select * from categories order by name" );
	while($c=$cres->fetch_object()) {
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
	$cresz=lib_mysql_query( "select * from categories order by name asc" );
	while($cc=$cresz->fetch_object()) {
		$res=lib_mysql_query( "select * from admin_menu where category = '$cc->name' order by name asc" );
		while($menuitem=$res->fetch_object()) {
			$tdlc++;
			if( $tdlc==2 ) $tdlc=0;
            adm_action_f_admin_menu_edit_entry_data($menuitem->id,$tdlc);
		}
	}
	echo "</table>";
	echo "<br><br>";
	include( "footer.php" );
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_MENU TOP
function adm_action_f_menu_topedit_del_go() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from menu_top where `id`='$id'" );
	$menuitem=$res->fetch_object();
	echo "<h3>Edit Top Menu :: Delete $menuitem->name :: DELETED!</h3>";
	lib_mysql_query( "delete from menu_top where `id`='$id'" );
	adm_action_menu_topedit();
	exit();
}
function adm_action_f_menu_topedit_del() { eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from menu_top where `id`='$id'" );
	$menuitem=$res->fetch_object();
	echo "<h3>Edit Top Menu :: Delete $menuitem->name</h3>";
	echo "<form enctype=\"application/x-www-form-URLencoded\" method=\"post\" action=\"$RFS_SITE_URL/admin/adm.php\">";
	echo "<input type=hidden name=action value=f_menu_topedit_del_go>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=submit name=submit value=confirm></form>";
    lib_menus_options();
	exit();
}
function adm_action_f_menu_top_add_link() {
    eval(lib_rfs_get_globals());
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
function adm_action_f_menu_topedit_add() {
    eval( lib_rfs_get_globals());
	echo "<h3>Edit Top Menu :: Add $mname</h3>";
	echo "$mname <br>";
	echo "$menu_url<br>";
	echo "$target<br>";
	echo "$msor<br>";
	echo "$access_method<br>";
	lib_mysql_query( "insert into menu_top (   `name`,      `link`, `target`, `sort_order`, `access_method`)
								  values('$mname', '$menu_url', '$target',     '$msor', '$access_method');" );
    adm_action_menu_topedit();
	exit();
}
function adm_action_f_menu_topedit_mod() {
    eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from menu_top where `id`='$id'" );
	$menuitem=$res->fetch_object();
	echo "<h3>Edit Top Menu :: Modify $menuitem->name = $mname</h3>";
	lib_mysql_query( "update menu_top set `name`='$mname' where `id`='$id'" );
	lib_mysql_query( "update menu_top set `link`='$menu_url' where `id`='$id'" );
	lib_mysql_query( "update menu_top set `target`='$target' where `id`='$id'" );
	lib_mysql_query( "update menu_top set `sort_order`='$msor' where `id`='$id'" );
	lib_mysql_query( "update menu_top set `access_method`='$access_method' where `id`='$id'" );
    adm_action_menu_topedit();
	exit();
}
function adm_action_menu_topedit() {
    eval( lib_rfs_get_globals() );
	echo "<h1>Edit Top Menu</h1><hr>";
	echo "<table border=0 cellspacing=0 cellpadding=0>";
	echo "<tr>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "<td class=contenttd> name </td>";
	echo "<td class=contenttd> link </td>";
	echo "<td class=contenttd> target </td>";
	echo "<td class=contenttd> sort order </td>";
	echo "<td class=contenttd> access method </td>";
	echo "<td class=contenttd> &nbsp; </td>";
	echo "</tr>";
	$res=lib_mysql_query( "select * from menu_top order by sort_order asc" );
	for( $i=0; $i<$res->num_rows; $i++ ) {
		$menuitem=$res->fetch_object();
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
		echo "<input size=\"5\" type=\"text\" name=\"msor\" value=\"$menuitem->sort_order\">";
		echo "</td>";
		echo "<td class=\"contenttd\">";
		echo "<input size=\"15\" type=\"text\" name=\"access_method\" value=\"$menuitem->access_method\">";
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
	echo "<input size=5 name=msor>";
	echo "</td>";
	echo "<td class=contenttd>";
	echo "<input size=15 name=access_method>";
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
	eval( lib_rfs_get_globals() );
	$_SESSION['select_image_path']="";
	lib_images_select( "images","admin/adm.php","edit_categories", "categories", $id, "image" );
	exit();
}
function adm_action_f_delete_category() {
	eval( lib_rfs_get_globals() );
	echo "<p>Category $category deleted</p>";
	lib_mysql_query( "delete from categories where `name`='$category'" );
	adm_action_edit_categories();
}
function adm_action_f_add_category() {
	eval( lib_rfs_get_globals() );
	echo "<p>Added category $category</p>";
	lib_mysql_query( "insert into categories (`name`, `image`, `worksafe` ) values ('$category', '$image', '$worksafe')" );
	adm_action_edit_categories();
}
function adm_action_f_rename_category() {
	eval( lib_rfs_get_globals() );
	echo "<p>Renamed category from $category to $newname</p>";
	lib_mysql_query( "update categories set image='$image' where name = '$category'" );
	lib_mysql_query( "update categories set name='$newname' where name = '$category'" );
	lib_mysql_query( "update categories set worksafe='$worksafe' where name = '$category'");
	lib_mysql_query( "update admin_menu set category = '$newname' where category = '$category'" );
	adm_action_edit_categories();
}
function adm_action_edit_categories() {
	echo "<h1>Edit Categories</h1><hr>";
	eval( lib_rfs_get_globals() );
	$result=lib_mysql_query( "select * from categories order by name asc" );
	if(!$result) echo "<p>There are no categories!</p>\n";
	echo "<table border=0>";
	echo "<tr><td>&nbsp;</td><td>
	<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">
	<input type=hidden name='action'   value='f_add_category'>
	<input type=text   name='category' value='' style=' width: 100%;'>
	</td><td><img src=$RFS_SITE_URL/images/icons/exclamation.png width=64 height=64 border='0'>
	</td><td><input type=text name=image value=''>
	</td><td><input type=text name=worksafe value=''></td><td><div class=menutop><input type=submit name=submit        value=add>
	</div></form></td></tr>";
	while($cat=$result->fetch_object()) {		
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
		echo "</td><td>
		<a href='$RFS_SITE_URL/admin/adm.php?action=f_category_change_icon&id=$cat->id'>		
		<img src='$RFS_SITE_URL/$cat->image' border='0' width=64 height=64> </a>
		</td><td><input type=text name=image value='$cat->image'></td><td><input type=text name=worksafe value='$cat->worksafe'>
		</td><td>\n";
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
	eval( lib_rfs_get_globals() );
	echo "<h3>User updated</h3>";
	lib_mysql_update_database( "users","id",$id,1 );
	adm_action_user_edit();
}
function adm_action_f_edit_users() { eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from `users` where `id`='$id'" );
	$user=$res->fetch_object();
	echo "<h3>Editing User [$user->name]</h3>";
	lib_forms_build( "$RFS_SITE_URL/admin/adm.php","action=f_edit_users_go".$RFS_SITE_DELIMITER."id=$id","users","select * from `users` where `id`='$id'","","id".$RFS_SITE_DELIMITER,"omit","",60,"update" );
	include("footer.php");
	exit();
}
function adm_action_f_del_users_go() {
	eval( lib_rfs_get_globals() );
	$res=lib_mysql_query( "select * from `users` where `id`='$id'" );
	$user=$res->fetch_object();
	if( $yes=="Yes" ) {
		echo "User $user->name removed from database";
		lib_mysql_query( "delete from `users` where `id`='$id'" );
	}
	adm_action_user_edit();
}
function adm_action_f_del_users() {
	eval( lib_rfs_get_globals() );
	echo lib_string_convert_smiles( "<p class=warning>^X<br>WARNING!<BR></p>" );
	$res=lib_mysql_query( "select * from `users` where `id`='$id'" );
	$user=$res->fetch_object();
	lib_forms_confirm( "Delete $user->name?",
                    "$RFS_SITE_URL/admin/adm.php",
                    "action=f_del_users_go".$RFS_SITE_DELIMITER."id=$id" );
	include("footer.php");
	exit();
}
function adm_action_f_add_user() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Add user $name</h3>";
	$pmd5=md5( $pass );
	lib_mysql_query( "insert into `users` (`name`,`pass`) VALUES ('$name','$pmd5');" );
	adm_action_user_edit();
}
function adm_action_user_edit() {
	eval( lib_rfs_get_globals() );
	echo "<div class=forum_box>";
    echo "<h1>User Editor</h1>";
    $uol=lib_users_online(); 
    $uli=lib_users_logged_in();
	lib_forms_info("Users online [$uol] Users logged in [$uli] ".lib_users_logged_details(),"white","blue");
	echo "<h2>Add User</h2>";
	lib_forms_build(  "$RFS_SITE_URL/admin/adm.php","action=f_add_user", "users", "","", "name".$RFS_SITE_DELIMITER."pass","include", "", 20, "add new user" );
	lib_mysql_dump_table( "users,id,first_name,last_name,name,email,donated,forumposts,forumreplies,downloads,uploads","showform".$RFS_SITE_DELIMITER."f_","id","" );
	echo "</div>";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_RSS EDITOR
/*function adm_action_f_rss_edit_go_edit() {
	eval( lib_rfs_get_globals() );
	if( $update=="update" ) lib_mysql_query( "UPDATE rss_feeds SET `feed`='$edfeed' where `id`='$oid'" );
	if( $delete=="delete" ) lib_mysql_query( "DELETE FROM rss_feeds WHERE id = '$oid' " );
	adm_action_rss_edit();
}
function adm_action_f_rss_edit_go_add() {
	eval( lib_rfs_get_globals() );
	lib_mysql_query( "insert into rss_feeds values('$edfeed',0);" );
	adm_action_rss_edit();
}
function adm_action_rss_edit() {
	eval( lib_rfs_get_globals() );
	$result=lib_mysql_query( "select * from rss_feeds" );
	$num_feeds=$result->num_rows;
	echo "<h3>Editing RSS Feeds </h3>";

	for( $i=0; $i<$num_feeds; $i++ ) {
		echo "<table border=0 cellspacing=0 cellpadding=0>\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=rsseditgoedit>\n";
		$feed=$result->fetch_object();
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
}*/
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_SMILEY EDITOR
function adm_action_edit_smilies() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Smiley Editor</h3>";
	
	$sto=stripslashes( $sto );
	$sfrom=stripslashes( $sfrom );
	$ofrom=stripslashes( $ofrom );
	switch( $smact ) {
		case "update":
			$sfrom=addslashes( $sfrom );
			lib_rfs_echo("$sfrom -> $sto updated");
			$sto=addslashes( $sto );
			lib_mysql_query( "UPDATE `smilies` SET `sto`='$sto' where `sfrom`='$ofrom';" );
			lib_mysql_query( "UPDATE `smilies` SET `sfrom`='$sfrom' where `sfrom` = '$ofrom';" );
			break;
		case "delete":
			$sfrom=addslashes( $sfrom );
			echo "$sfrom -> $sto deleted";
			$sto=addslashes( $sto );
			lib_mysql_query( "delete from `smilies` where `sfrom` = '$sfrom' and `sto` = '$sto' limit 1;" );
			break;
		case "new":

			$sfrom=addslashes( $sfrom );
			echo "$sfrom -> $sto created";
			$sto=addslashes( $sto );
			lib_mysql_query( "INSERT INTO `smilies` (`sfrom`, `sto`) VALUES ('$sfrom', '$sto');" );
			break;
	}
	echo "<table width=100% cellspacing=0 cellpadding=0>\n";
	echo "<tr>\n";
	echo "<td class=contenttd>";
	$result=lib_mysql_query( "select * from smilies" );	
	echo "<td class=contenttd>\n";
	echo "<table border=0 cellspacing=0 cellpadding=5 width=100%>";
	while($smiley = $result->fetch_array()) {
		$bg=$bg+1; if( $bg>2 ) $bg=1;		
		$sfrom=stripslashes( $smiley['sfrom'] );
		$sto=stripslashes( $smiley['sto'] );
		echo "<tr>\n";
		lib_rfs_echo( "<td class=rfs_project_table_$bg align=center width=24 valign=top>$sto</td>");
		echo "<td class=rfs_project_table_$bg valign=top>\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=edit_smilies>\n";
		echo "<input type=hidden name=smact value=update>";
		echo "<input type=hidden name=ofrom value=\"$sfrom\">\n";
		echo "</td>";
		echo "<td class=rfs_project_table_$bg>";
		echo "<input size=5 type=textbox name=sfrom value=\"$sfrom\">\n";
		echo "</td>";
		echo "<td class=rfs_project_table_$bg>";
		echo "<textarea name=sto cols=80>$sto</textarea>\n";
		echo "</td>";
		echo "<td class=rfs_project_table_$bg>";
		echo "<input type=submit name=smact value=update></form></td>";
		echo "<td class=rfs_project_table_$bg>";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=edit_smilies>\n";
		echo "<input type=hidden name=smact value=delete>";
		echo "</td>";
		echo "<td class=rfs_project_table_$bg>";
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
	eval( lib_rfs_get_globals() );
	lib_mysql_query( "insert into `quiet` (`ip`,`domain`) values(\"$domain\",\"$domain\")" );
}
function adm_action_f_delete_log_go() {
	eval(lib_rfs_get_globals());
	$rm="rm";
	if($RFS_SITE_OS=="WIN") $rm="del";
	system("$rm $RFS_SITE_PATH/log/$wlog");
	adm_action_f_view_old_logs();
}
function adm_action_f_delete_log() {
	echo "<div class=forum_box>";
	echo "<h3>Delete Log</h3>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=log_view","View Current Log");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_view_old_logs","View Old Logs");
	echo "<hr>";
	eval(lib_rfs_get_globals());
	lib_forms_confirm("Delete $wlog","$RFS_SITE_URL/admin/adm.php?action=f_delete_log_go&wlog=$wlog","what");
	echo "</div>";
	include("footer.php");
	exit();	
	
}
function adm_action_f_log_rotate() {
	eval(lib_rfs_get_globals());
	$t=time();
	$mv="mv";
	$cp="cp";
	if($RFS_SITE_OS=="WIN") {
		$mv="rename";
		$cp="copy";
	}
	echo "$mv $RFS_SITE_PATH/log/log.htm $RFS_SITE_PATH/log/log_$t.htm";
	system( "$mv $RFS_SITE_PATH/log/log.htm $RFS_SITE_PATH/log/log_$t.htm" );
	echo "$cp $RFS_SITE_PATH/log/blanklog.htm $RFS_SITE_PATH/log/log.htm";
	system( "$cp $RFS_SITE_PATH/log/blanklog.htm $RFS_SITE_PATH/log/log.htm" );
	lib_log_add_entry( "Log restarted" );
	adm_action_log_view();
}
function adm_action_f_view_old_logs_go() {
	eval(lib_rfs_get_globals());
	echo "<div class=forum_box>";
	echo "<h3>View Log</h3>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=log_view","View Current Log");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_view_old_logs","View Old Logs");
	echo "<hr>";
	$x=file_get_contents("$RFS_SITE_PATH/log/$wlog");
	$x=str_replace("¥","<br>",$x);
	$x=str_replace("<t","&lt;t",$x);
	$x=str_replace("<for","&lt;for",$x);
	$x=str_replace("<i","&lt;i",$x);
	$x=str_replace("<s","&lt;s",$x);
	$x=str_replace("<d","&lt;d",$x);
	$x=str_replace("</t","&lt;/t",$x);
	$x=str_replace("</for","&lt;/for",$x);
	$x=str_replace("</i","&lt;/i",$x);
	$x=str_replace("</s","&lt;/s",$x);
	$x=str_replace("</d","&lt;/d",$x);
	echo $x;
	echo "</div>";
	include("footer.php");
	exit();
}
function adm_action_f_view_old_logs() {
	echo "<div class=forum_box>";
	echo "<h3>View Old Logs</h3>";
	echo "<hr>";
	eval(lib_rfs_get_globals());
	$logs=lib_file_folder_to_array("$RFS_SITE_PATH/log");
	echo "<table border=0>";
	foreach($logs as $a => $b) {
		echo "<tr>";
		echo "<td>";
		lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_delete_log&wlog=$b","Delete");
		echo "</td>";
		echo "<td>";
		lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_view_old_logs_go&wlog=$b","$b");
		echo "</td>";
		echo "<td>";		
		echo lib_file_get_size("$RFS_SITE_PATH/log/$b");
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
	include("footer.php");
	exit();
}
function adm_action_log_view() {
	eval( lib_rfs_get_globals() );
	echo "<div class=forum_box>";
	echo "<h3>View Log</h3>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_log_rotate","Rotate Log");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_view_old_logs","View Old Logs");
	echo "<hr>";	
	@include( "$RFS_SITE_PATH/log/log.htm" );
	echo "</div>";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_COUNTERS
function adm_action_counters() {
	echo "<div class=forum_box>";
	echo "<h3>Counters</h3>\n";
	$hits_raw=$_REQUEST['hits_raw']; if(empty($hits_raw)) $hits_raw=50;
	echo "Showing pages with at least $hits_raw hits<br>";
	echo "<table>";
	$r=lib_mysql_query("select * from counters where hits_raw > $hits_raw");
	for($x=0;$x<$r->num_rows;$x++){
		$counter=$r->fetch_object();
		echo "<tr><td width='200'>";
		echo "$counter->user_timestamp ";
		echo "</td><td>";
		echo "$counter->last_ip";
		echo "</td><td>";
		echo "$counter->hits_raw";
		echo "</td><td>";
		echo "$counter->hits_unique ";
		echo "</td><td>";
		echo lib_string_truncate($counter->page,200);
		echo "</td><tr>";
	}
	echo "<table>";	
	echo "</div>";
	include("footer.php");
	exit();
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_AWARD EDIT
// function adm_action_awards_edit() { 	echo "<h3>Award Editor</h3>\n"; 	rfs_awards_list(); 	include("footer.php");	exit();}
function adm_action_f_add_award_go() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Add award!</h3>\n";
	// id, name, description, image, time
	$name=addslashes( $name );
	$description=addslashes( $description );
	$image=addslashes( $image );
	$time=date( "Y-m-d H:i:s" );
	lib_mysql_query( "insert into awards values('', '$name', '$description', '$image', '$time')" );
	adm_action_awards_edit();
}
function adm_action_f_edit_award_go() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Edit award!</h3>\n";
	// id, name, description, image, time
	$name=addslashes( $name );
	$description=addslashes( $description );
	$image=addslashes( $image );
	lib_mysql_query( "update awards set name = '$name' where id = '$id'" );
	lib_mysql_query( "update awards set description = '$description' where id = '$id'" );
	lib_mysql_query( "update awards set image = '$image' where id = '$id'" );
	adm_action_awards_edit();
	
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_EDIT_TAGS (HIDE THIS FOR NOW)
function adm_action_f_edit_tags() {
    eval(lib_rfs_get_globals()); 
	echo "<h1>Edit Tags</h1><hr>";
	lib_mysql_scrub("tags","tag");
	echo "<p>Hidden tags will not be shown to the public. They won't even show up in listings of available tags.</p>";
	echo "<div style='clear:both;' class='rfs_file_table_$gt'  >";
	echo "<div style='float:left; width:170px;'>";		
	echo "Tag";
    echo "</div>";
	echo "<div style='float:left;'>";
	echo "Hidden";
	echo "</div>";
	echo "</div>";
    $r=lib_mysql_query("select * from tags order by tag asc");
	while($tag=$r->fetch_object()) {
		$gt++;if($gt>1) $gt=0;		
		echo "<div style='clear:both;' class='rfs_file_table_$gt'  >";
			echo "<div style='float:left;'>";		
				lib_ajax("Tag,80"			  	, "tags"  	,  "id",    "$tag->id",      "tag",       "", "nohide,nolabel",	"admin", "access", "");
			echo "</div>";
			echo "<div style='float:left;'>";
				lib_ajax("Hidden,30"		  	, "tags"  	,  "id",    "$tag->id",   "hidden",       "", "nohide,nolabel",	"admin", "access", "");
			echo "</div>";
		echo "</div>";
	}
    finishadminpage();
 }
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_DISK_FREE
function adm_action_disk_free() {
    eval(lib_rfs_get_globals());
	echo "<h1>Disk Usage</h1><hr>";
	echo "<div class='wikishell'>";	
	echo "<pre>";
	$x=array();
	array_push($x,"===================================================================");
	if($RFS_SITE_OS=="X") exec("df -h",$x);
	if($RFS_SITE_OS=="WIN") exec("windows command for diskfree",$x); //TODO here
	array_push($x,"===================================================================");
	foreach($x as $k => $v) 
		echo $v."\n";
	echo "</pre>";
	echo "</div>";
	finishadminpage();					  
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_DEFAULT_ACTION
function adm_action_() {
    eval(lib_rfs_get_globals());
	if($db_queries=="list" ) {
        adm_db_query_list();
        exit();
    }
	$ax=lib_mysql_fetch_one_object("select * from access where name='Administrator' and action='access' and page='admin'");
	if(!$ax->id) {
		lib_mysql_query("insert into access (name,action,page) values('Administrator','access','admin')");
	}
	if( !empty( $_GET['admed'] ) )
        $_SESSION['admed']=$_GET['admed'];
	$data=lib_users_get_data( $_SESSION['valid_user'] );
	if(!lib_access_check("admin","access"))
        return;	
	echo "<div class='forum_box'  >";
	echo "<h1>Administration Panel</h1>";

	// start gathering $info	
	$info="Running RFSCMS version $RFS_VERSION ( BUILD $RFS_BUILD )<br>";	
	if(lib_rfs_bool_true($RFS_SITE_CHECK_UPDATE)) {
        system("rm vercheck");
        system("rm buildcheck");
        system("wget -O vercheck   https://raw.github.com/sethcoder/rfscms/master/include/version.php");
        system("wget -O buildcheck https://raw.github.com/sethcoder/rfscms/master/build.dat");
        $rver="remote version unknown"; 
        $file=fopen("vercheck", "r");  if($file) { $rver=fgets($file,256); fclose($file); }
        $file=fopen("buildcheck","r"); if($file) { $rbld=fgets($file,256); fclose($file); }
        system("rm vercheck");
        system("rm buildcheck"); 
        $rverx=explode("\"",$rver);
        if( ($RFS_VERSION!=$rverx[1]) ||
            (intval($RFS_BUILD)!=intval($rbld))) {
			$info.="NEW VERSION AVAILABLE: ".$rverx[1]." ( BUILD $rbld )"."[<a href=\"$RFS_SITE_URL/admin/adm.php?action=update\">Update Now</a>] <br>";
        }
        else {			
			$info.="Up to date, no new updates.<br>";
        }
	}
	$info.="UPTIME: ".exec("uptime")."<br>";
	$info.=exec("uname -a")."<br>";
	$git=exec("git --version");
	if(!empty($git)) {
			$info.="GIT version installed: $git <br>";
		}
	// dump $info 	
	lib_forms_info($info,"green","black");
	
	
	
	echo "<table border=0><tr><td>";
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?debug=on","Debug on <font style='color: green; background-color: dark-green;'> ON </font>" );
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?debug=off","Debug <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?admed=on&what=1","Adm Edit <font style='color: green; background-color: dark-green;'> ON </font>" );
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?admed=off&what=1","Adm Edit <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?textbuttons=true","Text Buttons <font style='color: green; background-color: dark-green;'> ON </font>" );
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?textbuttons=false","Text Buttons <font style='color:red; background-color: dark-green;'> OFF </font>" );
	echo "</td><td>";
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?admin_show_top=hide","Hide banner" );
	lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?admin_show_top=show","Show banner" );
	echo "</td></tr></table>";	
	echo "<hr>";
    adm_menu_built_in();
	echo "</div>";
	finishadminpage();
}
function adm_menu_built_in() {
    eval(lib_rfs_get_globals());
    $arr=get_defined_functions();
	asort($arr['user']);
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
			
echo "<div style='
float:left;
border: 1px solid #000000;
margin: 5px;
padding:5px 10px;
background:#454525;
border-radius:12px;
width: 95px;
height: 95px;
text-align: center;
'>";
								echo "<a href=\"$RFS_SITE_URL/admin/adm.php?action=$lx\" $target>";
								$imglnk="<img src='$RFS_SITE_URL/admin/images/";
								$image="";
								if( file_exists( "$RFS_SITE_PATH/admin/images/$x.png" )) $image="$x.png'";
								if( file_exists( "$RFS_SITE_PATH/admin/images/$x.gif" )) $image="$x.gif'";
								if( file_exists( "$RFS_SITE_PATH/admin/images/$x.jpg" )) $image.="$x.jpg'";
								
								// TODO: If the function is from a module, search the module/images folder
								
								if(empty($image)) {
									$imx=explode("_",$x);
									$module_name=$imx[0];
									lib_modules_get_name($module_name);
									$core=lib_modules_get_property($module_name,"Core");
									$author=lib_modules_get_property($module_name,"Author");
									echo "--$module_name ($core $author)--";
									
									
								}								
								$imglnk.=$image." width=64 height=64 border='0' align=center>";
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
	echo "<div style='clear: left; '>&nbsp;</div>";	
	
	
	
	$res=lib_mysql_query("select * from `admin_menu`");
	$x=$res->num_rows;
	if($x>0) { 
		echo "<h1>Custom Admin Buttons</h1>";
		$cres=lib_mysql_query( "select * from categories order by name asc" );
		for( $ci=0; $ci<$cres->num_rows; $ci++ ) {
			$cc=$cres->fetch_object();
			$res=lib_mysql_query( "select * from admin_menu where category = '$cc->name' order by name asc" );
			if($res->num_rows) {
				for( $i=0; $i<$res->num_rows; $i++ ) {
				$icon=$res->fetch_object();
				

echo "<div style='
float:left;
border: 1px solid #000000;
margin: 5px;
padding:5px 10px;
background:#357535;
border-radius:12px;
width: 95px;
height: 95px;
text-align: center;
'>";
					
					echo "<a href=\"";
					$icon->url=str_replace( ";","%3b",$icon->url );
					lib_rfs_echo( $icon->url );
					echo "\" target=\"$icon->target\">";
					if(!file_exists("$RFS_SITE_PATH/$icon->icon"))
						$icon->icon="images/icons/exclamation.png";
					echo "<img src=\"$RFS_SITE_URL/include/button.php?im=$RFS_SITE_PATH/$icon->icon&t=$icon->name&w=64&h=64&y=20\" border='0'></a><br>";
					echo "<a href=\""; 
					$icon->url=str_replace( ";","%3b",$icon->url );
					lib_rfs_echo( $icon->url );
					echo "\" target=\"$icon->target\" style='color: #cFcF00;'>";
					echo ucwords(str_replace("_"," ",$icon->name));
					echo "</a>";
					if( $_SESSION['admed']=="on" ) {
							lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_edit_entry&id=$icon->id","Edit" );
							lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_edit_del&id=$icon->id","Delete" );
							lib_buttons_make_button( "$RFS_SITE_URL/admin/adm.php?action=f_admin_menu_change_icon&id=$icon->id","Change Icon" );
							lib_forms_optionize( "$RFS_SITE_URL/admin/adm.php","action=f_admin_change_category".$RFS_SITE_DELIMITER."id=$icon->id","categories","name",0,$cc->name,1);
					}
					echo "</div>";
				}
			}
		}
	}
}

function finishadminpage() {
	eval( lib_rfs_get_globals() );
	if(!lib_access_check("debug","view")) return;
		if(isset($_SESSION['debug_msgs'])) {
		if(lib_rfs_bool_true($_SESSION['debug_msgs'])){    
			d_echo("======================================================================");
			d_echo("FUNCTION LIST:");
			d_echo("======================================================================");
			$arr=get_defined_functions();
			natcasesort($arr['user']);
			foreach( $arr['user'] as $k=>$v ) {
				d_echo($v);	
			}
		}
	}
	include( "footer.php" );
	exit();
}

?>
