<?
////////////////////////////////////////////////////////////
// RFS WAB Web Application Builder by Seth Parson

chdir("../../");
include("header.php");

$wab_version="v1.9";
if(empty($runapp)) $runapp=$_SESSION['runapp'];
if(!empty($_REQUEST['runapp'])) {
    $_SESSION['runapp']=$_REQUEST['runapp'];
    $runapp=$_REQUEST['runapp'];
}
if(empty($runapp)) $runapp="1";

$wab_engine   =mfo1("select * from `wab_engine` where `id`='$runapp'");
$wab_database=$wab_engine->value;

if(empty($wab_database)) {
    echo "Database for this app is undefined!<br>";
    $wab_database="wab_engine";
}

$_SESSION["runapp"]=$wab_engine->id;
$name=ucwords(str_replace("_"," ",$wab_engine->name));

$lnk= "$RFS_SITE_URL/modules/wab/wab.php?runapp=1";

if($data->access==255) {
    if($_SESSION['hide_wab_admin_menu']!=true) {
        echo "<table border=0 width=100%><tr><td align=left class=sc_project_table_0>";
        echo "WAB Engine $wab_version ";
        echo "[<a href=$RFS_SITE_URL/modules/wab/wab.php?runapp=1&action=editapp&edapp=$runapp>edit this app</a>] ";
        echo "[<a href=$lnk>List all Apps</A>] ";
        echo "[<a href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?name=RFS+Website+Application+Builder\">What is this?</a>] ";
        echo "</td><td align=left class=sc_project_table_0>";
        sc_bf(  sc_phpself(),
                        "action=hide_wab_admin_menu,".
                        "runapp=".mfo1("select * from `wab_engine` where `name`='wab_engine' and `parent`=`id`")->id.",".
                        "gotoapp=$wab_engine->id",
                        "", "", "", "", "", "", 20, "Hide this");
        echo "</td></tr></table>";
    }
}

echo "<table border=0 width=$site_singletablewidth><tr><td>";
//eval functions
$res=sc_query("select * from `$wab_database` where `type`='function' and `parent`='$wab_engine->id'");
while($wab_engine_function=mfo($res)) {
	//echo stripslashes($wab_engine_function->code)."<br>";
    eval(stripslashes($wab_engine_function->code));	
}
$wbna="$wab_engine->name"."_action_";
$wbna=str_replace(" ","_",$wbna);
no_func($wbna);

function no_func($wbna){
    if(!function_exists($wbna)){
        $we=mfo1("select * from `wab_engine` where `name`='wab_engine' and `parent`=`id`");
        $ec=" function $wbna() { eval(scg());
				echo \"<p class=warning>$wbna() function is undefined!</p>\";
				\$lnk=\"\$RFS_SITE_URL/modules/wab/wab.php?action=edcode\";
				\$lnk.=\"&runapp=".$we->id."\";
				\$lnk.=\"&edapp=\";
				\$lnk.=\$GLOBALS[\"runapp\"];
				\$lnk.=\"&_function=$wbna\";
				echo \"[<a href=\$lnk>\";
				echo \"edit $wbna() code\";
				echo \"</a>]\"; } ";
       eval($ec);
    }
}

$found_action=0;
$res=sc_query("select * from `$wab_database` where `type`='action' and `parent`='$wab_engine->id'");
while($wab_engine_action=mfo($res)){
    if($action==$wab_engine_action->value){
        $found_action=1;
        eval($wab_engine_action->code);
    }
}
// not in database, look in actual file for action function
if($found_action==0){
	// echo " ---- $action ---- <br>";
    no_func(str_replace(" ","_",$wab_engine->name)."_action_$action");    
    $funk=str_replace(" ","_",$wab_engine->name)."_action_$action();";
    eval($funk);
}
eval($wab_engine->code);
echo "</td></tr></table>";
include("footer.php");
exit();

////////////////////////////////////////////////////////////
// FUNCTIONS
function wab_engine_action_(){ eval(scg());
    echo "<p>WAB Engine $wab_version by Seth Parson.</p><p>Available Apps:</p>";    
    $gt=0;
    echo "<table border=0>";
    $data=$GLOBALS['data'];
    $res=sc_query("select * from wab_engine where parent=id");
    while($app=mfo($res)) {   
        if(!$app->hidden) {
            $gt++; if($gt>1) $gt=0;
            echo "<tr><td class=sc_project_table_$gt>";		
            echo "<a href=\"$RFS_SITE_URL/modules/wab/wab.php?runapp=$app->id\">$app->name</a> ";
            echo "</td><td class=sc_project_table_$gt>";
            if($data->access==255) {
                if($_SESSION['hide_wab_admin_menu']!=true)
                    sc_bf(  sc_phpself(),
                        "action=editapp,".
                        "edapp=$app->id",
                        "", "", "", "", "", "", 20, "Edit");
            }
            echo "</td></tr>";
        }
    }
    if($data->access==255){
        echo "<tr><td class=sc_project_table_$gt></td>";
        echo "<td class=sc_project_table_$gt>";        
		if($_SESSION['hide_wab_admin_menu']==true){
				sc_bf(  sc_phpself(),"action=show_wab_admin_menu,","", "", "", "", "", "", 20, "Show WAB Admin");
		}                        
		else{        
				sc_bf(  sc_phpself(), "action=add_form,", "", "", "", "", "", "", 20, "Start a new App");        
		}
        echo "</td></tr>";
        
    }
    echo "</table>";
}

function wab_engine_action_edcodego() {
    $edapp=$GLOBALS['edapp'];
    $app=mfo1("select * from `wab_engine` where `id`='$edapp'");
    $db =mfo1("select * from `wab_engine` where `parent`='$edapp' and `type`='database'");
    sc_updb("$db->value","value",$_REQUEST['value']);
    sc_query("update `$db->value` set `name`='$app->name' where `value`='".$_REQUEST['value']."'");
    echo "<p class=warning>FUNCTION: [".$_REQUEST['value']."] updated</p>";
    wab_engine_action_editapp();
}

function wab_engine_action_edcode() {
    echo "CODE EDITOR<br>";    
    $_function=$GLOBALS['_function'];
    $edapp=$GLOBALS['edapp'];
    $app=mfo1("select * from `wab_engine` where `id`='$edapp' and `parent`='0'");
    $db =mfo1("select * from `wab_engine` where `parent`='$edapp' and `type`='database'");
    echo "Editing $app->name::$_function()<br>";
    sc_bf(  sc_phpself(),
            "action=edcodego,type=function,".
            "SHOW_CODEAREA_35#140#code=l,hidden=1,".
            "TT_35#140#code=codearea,".
            "edapp=$edapp,value=$_function,parent=$edapp,".
            "code=function $_function() {
					echo '$_function()!<br>';
					},".
            "name=$app->name",
            "$db->value",
            "select * from `$db->value` where `type`='function' and `value`='$_function';",
            "", "value,code", "include", "",60,"submit");
}

function wab_engine_action_add() {
    if($GLOBALS['data']->access==255)  {
        sc_updb("wab_engine","name",$_REQUEST['name']);
        sc_query("update `wab_engine` set `parent`=`id` where `name`='".$_REQUEST['name']."'");
        sc_query("update `wab_engine` set `type`='database' where `name`='".$_REQUEST['name']."'");
        sc_query("update `wab_engine` set `value`='wab_engine' where `name`='".$_REQUEST['name']."'");
        sc_query("update `wab_engine` set `description`='".$_REQUEST['name']."' where `name`='".$_REQUEST['name']."'");
        echo "<br>";
        wab_engine_action_();
    }
}

function wab_engine_action_add_form() {
    if($GLOBALS['data']->access==255) {
        echo "<h3>Start a new application:</h3>";
        sc_bf( sc_phpself(), "action=add,parent=0,hidden=0", "wab_engine", "", "", "name", "include", "",30,"add");
    }
}

function wab_engine_action_editapp() { eval(scg());
    $id=$_REQUEST['edapp'];
    $res=sc_query("select * from `wab_engine` where `id`='$id'");
    $co=mfo($res); 
    $wab_engine_name=ucwords(str_replace("_"," ",$co->name));
    echo "<table border=0><tr><td>";
    echo "<a href=\"$RFS_SITE_URL/modules/wab/wab.php?runapp=$id\">"; 
    echo "<img src=\"$RFS_SITE_URL/images/icons/button_play_blue.png\" width=32 height=32>";
    echo "</a>";
    echo "</td><td>Run app</td></tr></table>";
    echo "<p>COMPONENTS OF $wab_engine_name</p><p>";
    wab_database($id);
    wab_engine_actions($id);
    wab_variables($id);
    wab_functions($id);
    echo "</p>";
}

function wab_engine_action_add_database() {
    $table=$GLOBALS['value'];    
    $qr="CREATE TABLE IF NOT EXISTS `$table` (
		  `name` text COLLATE utf8_unicode_ci NOT NULL,
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `parent` int(11) NOT NULL DEFAULT '1',
		  `hidden` int(11) NOT NULL DEFAULT '1',
		  `type` text COLLATE utf8_unicode_ci NOT NULL,
		  `value` text COLLATE utf8_unicode_ci NOT NULL,
		  `description` text COLLATE utf8_unicode_ci NOT NULL,
		  `code` text COLLATE utf8_unicode_ci NOT NULL,
		   KEY `id` (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ; ";
    sc_query($qr);
    $nm=mfo1("select * from `wab_engine` where `id`='".$GLOBALS['parent']."'");
    $key_field="name";
    $key_value=$nm->name;
    echo "$table<BR>";
    echo "$key_field<BR>";
    echo "$key_value<BR>";
    sc_updb($table,$key_field,$key_value);
    sc_query("delete from `wab_engine` where `type`='database' and `parent`='".$GLOBALS['parent']."'");    
    sc_query("insert into `wab_engine` (`$key_field`,`type`,`value`,`parent`) values ('$key_value','database','$table','".$GLOBALS['parent']."')");    
}

function wab_engine_action_hide_wab_admin_menu() {
    $_SESSION['hide_wab_admin_menu']=true;
    gotopage("wab.php?runapp=".$_REQUEST['gotoapp']);
}

function wab_engine_action_show_wab_admin_menu() {
    $_SESSION['hide_wab_admin_menu']=false;
    refreshpage();
}

function wab_engine_action_add_function() {
    $nm=mfo1("select * from `wab_engine` where `id`='".$GLOBALS['parent']."'");
    $db=mfo1("select * from `wab_engine` where `parent`='".$GLOBALS['parent']."' and type='database'");
    $key_field="value";
    $key_value=$nm->name;
    sc_updb($db->value,$key_field,$key_value);
}

function wab_database($id) {
    echo "<p>";
    echo "DATABASE (table):<BR>";
    $db=mfo1("select * from `wab_engine` where `parent`='$id' and type = 'database'");
    if(sc_tableexists($db->value)) {
        echo "$db->type : $db->value<br>";
    }
    else {
        if(empty($db->value))
            echo"<font class=warning>No database defined!</font><br>";
        else
            echo"<font class=warning>Database defined as $db->value, but does not exist!</font><br>";
        $name=mfo1("select * from `wab_engine` where `id`='$id'");
        sc_bf(  sc_phpself(),
                "action=add_database,type=database,name=$name->name,".
                "parent=".$GLOBALS['edapp'],
                "wab_engine", 
                "", "",
                "value", "include", "",
                20, "Add Database");
    }
    echo "</p>";
}
function wab_variables($id) {
    echo "<p>";
    echo "VARIABLES:<BR>";
    $db=mfo1("select * from `wab_engine` where `parent`='$id' and type = 'database'");
    if(sc_tableexists($db->value)) {
        sc_db_query("SHOW FULL COLUMNS FROM $db->value" ,1);
    }
    else {
    }
    echo "</p>";
}

function wab_functions($id) {
    echo "<p>";
    echo "FUNCTIONS:<BR>";
    $db=mfo1("select * from `wab_engine` where `parent`='$id' and type = 'database'");    
    if(sc_tableexists($db->value)) {    
        $res=sc_query("select * from `$db->value` where `parent`='$id' and type = 'function'");
        while($co=mfo($res)) {
            echo "$co->type : $co->value --- [<a href=wab.php?action=edcode&edapp=".$GLOBALS['edapp']."&_function=$co->value>edit code</a>]<br>";
        }
 // $page, $hiddenvars, $table, $query, $hidevars, $specifiedvars, $svarf , $tabrefvars, $width, $submit
        $name=mfo1("select * from `wab_engine` where `id`='$id'");
        sc_bf(  sc_phpself(),
                "action=add_function,type=function,name=$name->name,".
                "parent=".$GLOBALS['edapp'].","."LABEL_value=Function", $db->value, "", "", "value", "include", "", 20, "Add Function");
    }
    else {
        echo "Define a database first<br>";
    }    
    echo "</p>";
}
function wab_engine_actions($id) {
    echo "<p>";
    echo "ACTIONS:<BR>";
    $res=sc_query("select * from `wab_engine` where `parent`='$id' and type = 'action'");
    while($co=mfo($res)) {
        echo "$co->type : $co->value --- [code]<br>";
    }
    echo "</p>";
}

function wab_form($action,$vars,$width,$submittxt) { eval(scg());
    $edapp=$GLOBALS['edapp'];
    $runapp=$GLOBALS['runapp'];
    if(!empty($vars)) {
        $varg=explode($RFS_SITE_DELIMITER,$vars);
        for($i=0;$i<count($varg);$i++) {
            if( (!stristr($varg[$i],"HIDDEN_")) &&
                (!stristr($varg[$i],"SHOW_")) &&
                (!stristr($varg[$i],"LABEL_")) )             {
                $varg[$i]="SHOW_TEXT_".$varg[$i];
            }
            $varg[$i]=str_replace("HIDDEN_","",$varg[$i]);
        }
        $vars=join($RFS_SITE_DELIMITER,$varg);
    }
    $varz="action=".$action;
    if(!empty($vars)) $varz.="$RFS_SITE_DELIMITER".$vars;
    sc_bf( sc_phpself(), $varz, $db, "", "", "", "omit", "", $width, $submittxt);
}

?>
