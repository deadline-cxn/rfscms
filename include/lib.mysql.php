<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(array_pop(explode("/",getcwd()))=="include")	chdir("..");
include_once("include/lib.div.php");
include_once("config/config.php");
include_once("include/session.php");
function lib_mysql_generate_content_ids() { eval(lib_rfs_get_globals());
	$q1="SHOW FULL TABLES";
	$r1=lib_mysql_query($q1);	
	while($t=mysql_fetch_array($r1)) {		
		$table=$t[0];
		$q2="DESCRIBE $table;";
		echo $q2."<br>";
		$hasid=false;
		$r2=lib_mysql_query($q2);
		if($r2)
		while($t2=mysql_fetch_array($r2)) {
			echo $t2[0]."<br>";
			if($t2[0]=="id") $hasid=true;
		}
		if($hasid) {
			echo " <font style='color:red;'>HAS ID!</font><BR>";
			$q3="INSERT INTO `contentid`(`table`,`table_id`) select '$table', `id` from `$table`";
			echo $q3."<br>";
			lib_mysql_query($q3);
		}
	}
}
function lib_mysql_setvar($table,$var,$set,$name,$sname) {
	lib_mysql_query("UPDATE `$table` SET `$var`='$set' where `$name` = '$sname'");
}
function lib_mysql_add($table, $field, $type, $default) {
	$q="CREATE TABLE IF NOT EXISTS `$table` ( `id` int NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
	lib_mysql_query($q);
	$q="ALTER TABLE `$table` add column `$field` $type $default;";
	lib_mysql_query($q);
}
function lib_mysql_data_add($table,$field,$value,$id) {
	$chkid=" ";
	if($id) { $chkid=" and `id`='$id' "; }
	$r=lib_mysql_fetch_one_object("select * from `$table` where `$field`='$value' $chkid");	
	if($r->id) return $r->id;
	lib_mysql_query("insert into `$table` (`$field`) VALUES ('$value'); ");
	$i=mysql_insert_id();
	return $i;	
}
function lib_mysql_hidden_var($name,$value) { echo lib_mysql_hidden_var_r($name,$value); }
function lib_mysql_hidden_var_r($name,$value) { return "<input type=\"hidden\" name=\"$name\" value=\"$value\">"; }
function lib_mysql_copy_row($table,$id) { lib_mysql_query("CREATE TEMPORARY TABLE `tmp` SELECT * FROM `$table` WHERE `id` = '$id'; ALTER TABLE `tmp` DROP `id`; INSERT INTO `$table` SELECT * FROM `tmp`;"); }
function lib_mysql_row_count($table) { $r=lib_mysql_query("select * from `$table`"); $n=mysql_num_rows($r); return $n; }
function lib_mysql_query_user_db($q){
    $r=lib_mysql_query_other_db($GLOBALS['userdbname'], $GLOBALS['userdbaddress'], $GLOBALS['userdbuser'],$GLOBALS['userdbpass'],$q);
    return$r;
}
function lib_mysql_query_other_db($db,$host,$user,$pass,$query){
$mysql=mysql_connect($host,$user,$pass);
mysql_select_db($db, $mysql);
$result=mysql_query($query,$mysql);
return $result;
}
function lib_mysql_delimiter($t){
	$d="\n";
	$sd=$GLOBALS['RFS_SITE_DELIMITER'];
	if(stristr($t,$sd)) $d=$sd;
	if(empty($d)) if(stristr($t,",")) $d=",";
	return $d;
}

function lib_mysql_import_sql($filename) {
	//	mysql -u username –-password=password database_name < file.sql 
	eval(lib_rfs_get_globals());
	// echo "Backing up $table to $filename<br>";
	return system("mysql -u $authdbuser --password=$authdbpass $authdbname < $filename");
}
function lib_mysql_backup_table($table,$filename) {
	eval(lib_rfs_get_globals());
	echo "Backing up $table to $filename<br>";
	return system("mysqldump -u$authdbuser -p$authdbpass $authdbname $table > $filename");
}
function lib_mysql_backup_database($filename) { eval(lib_rfs_get_globals());
	$tm=time();
	$a="mysqldump -u$authdbuser -p$authdbpass --databases $authdbname > ";
	$r1="$filename.$tm.sql";
	system($a.$r1);
	if( ( $userdbname == $authdbname ) && ( $userdbaddress == $authdbaddress) ){
			$r2="(user database is the same)";
	}
	else {
			$a="mysqldump -u $userdbuser -p$userdbpass --databases $userdbname > ";
			$r2="$filename.userdb.$tm.sql";
			system($a.$r2);
	}
	return ($r1."<br>".$r2."<br>");
}
function lib_mysql_open_database(){
	$mysql=@mysql_connect($GLOBALS['authdbaddress'],$GLOBALS['authdbuser'],$GLOBALS['authdbpass']);
	if(empty($mysql)) return false;	
	mysql_select_db( $GLOBALS['authdbname'], $mysql);
	return $mysql;
}
function lib_mysql_query($query) {	
	if(stristr($query,"`users`")) { return lib_mysql_query_user_db($query); }
	$mysql=lib_mysql_open_database(); if($mysql==false) return false;
	$result=mysql_query($query,$mysql);
	if(empty($result)) return false;
	return $result;
}
function lib_mysql_open_database_new() {
	$mysqli=new mysqli(	$GLOBALS['authdbaddress'],
							$GLOBALS['authdbuser'],
							$GLOBALS['authdbpass'],
							$GLOBALS['authdbname'] );
	if($mysqli->connect_errno) {
		echo "MySQL failed to connect (".$mysqli->connect_errno.") ".$mysqli->connect_error."<br>";
	}
	return $mysqli;
}
function lib_mysql_query_new($query) {
	if(stristr($query,"`users`")) { return lib_mysql_query_user_db($query); }
	return mysqli_query(lib_mysql_open_database(),$query);
}
function lib_mysql_describe_table($table){
    $query ="DESCRIBE $table";
    $result = mysql_query($query);
    while($i = mysql_fetch_assoc($result)){
         echo $i['Field'];
         echo "<br>";
    }
}
function lib_mysql_table_exists($table) {
    $r=lib_mysql_query("SELECT $table FROM information_schema.tables WHERE table_schema = '".$GLOBALS['authdbname']."' AND table_name = '$table';");
    return($r);
}
function lib_mysql_new_table($table){
	if(!lib_mysql_table_exists($table)){
        $dbn=$GLOBALS['authdbname'];
        $q="CREATE TABLE  `$dbn`.`$table` (`name` TEXT NOT NULL) ENGINE = MYISAM ;";
        echo "CREATING TABLE [$table]<br>";
        $r=lib_mysql_query($q);
    }
    else {
        echo "TABLE [$table] already exists!<br>";
    }
}
function lib_mysql_scrub($table,$group) {
	$tab2=$table."2";
	$tab3=$table."3";
	$q=" CREATE TABLE `$tab2` like `$table`; ";
	lib_mysql_query($q);
	$q=" INSERT `$tab2` SELECT * FROM `$table` GROUP BY $group;" ;
	lib_mysql_query($q);
	$q=" RENAME TABLE `$table`  TO `$tab3`; ";
	lib_mysql_query($q);
	$q=" RENAME TABLE `$tab2` TO `$table`; " ;
	lib_mysql_query($q);
	$q=" DROP TABLE `$tab3`; ";
	lib_mysql_query($q);
}
function lib_mysql_fetch_one_object($query){ $res=lib_mysql_query($query); if($res) return mysql_fetch_object($res); else return $res; }
function lib_mysql_table_to_array($table,$key,$kv,$field){
    $q="select $field from $table where $key = \"$kv\"";
    $res=lib_mysql_query($q);
    $i=mysql_fetch_assoc($res);
    reset($i);
    $j=current($i);
    return $j;
}
function lib_mysql_update_database($table,$key_field,$key_value,$md5_password) {
	$q="select * from `$table` where `$key_field`='$key_value' limit 1";
	d_echo("$q");
    $res=lib_mysql_query($q);
    if(mysql_num_rows($res)==0)
		lib_mysql_query("insert into `$table` (`$key_field`) values ('$key_value');");
    $res=lib_mysql_query("DESCRIBE $table");
    while($i = mysql_fetch_assoc($res)) {
		$q ="update $table set `";
		$q.=$i['Field'];
		$q.="`='";			
		$f=$_REQUEST["{$i['Field']}"];
		if($md5_password) {
			if(  ($i['Field']=="pass") ||
				 ($i['Field']=="password") ) {
					$f=md5($f);
				}
		}
		$q.=addslashes($f);
		$q.="' ";			
		$v=addslashes($key_value);
		$q.="where `$key_field`='$v'";
		d_echo("[$q]");
		lib_mysql_query($q);
    }
}
function lib_mysql_update_database_2($table,$key_field,$key_value){
	$res=lib_mysql_query("select * from `$table` where `$key_field`='$key_value' limit 1");
	if(mysql_num_rows($res)==0)
	lib_mysql_query("insert into `$table` (`$key_field`) values ('$key_value');");
	$res=lib_mysql_query("DESCRIBE $table");
	while($i = mysql_fetch_assoc($res)){
		//echo $i['Field']."::".$_REQUEST[$i['Field']]."<br>";
		if($_REQUEST[$i['Field']]!=''){
			$q ="update $table set `";
			$q.=$i['Field'];
			$q.="`='".addslashes($_REQUEST["{$i['Field']}"])."' ";
			$q.="where `$key_field`='".addslashes($key_value)."'";
			echo "$q<br>";
			//lib_mysql_query($q);
		}
	}
}
function lib_mysql_database_query($query,$becho){
	$gt=0;    
	if(stristr($query,"users")) 	 $res=lib_mysql_query_user_db($query);
	else                             $res=lib_mysql_query($query);
	if($res)
	if($becho){
		$num=@mysql_num_rows($res);
		echo "<br>$num rows affected<br>";
		echo "<table border=0 cellpadding=5>";
		$hdr=0;
		while($row=@mysql_fetch_assoc($res)){
			$gt++; if($gt>2) $gt=1;
			if($hdr==0){
                echo "<tr>";
                reset($row);
                while(key($row)!==NULL){
                    echo "<th>";
                    echo key($row);
                    echo "</th>";
                    next($row);
                }
                echo "</tr>";
                $hdr=1;
            }
            //$ra=array();
            //var_dump(array_keys($row));
            reset($row);
            //echo $row[key($row)]." ";
            echo "<tr>";
            while(key($row)!==NULL){
                echo "<td class=rfs_project_table_$gt>";
				
				
				
					$txtout=current($row);
					$txtout=str_replace("<","&lt;",$txtout);
					$txtout=str_replace(">","&gt;",$txtout);
					echo $txtout;
				
				
                echo "</td>";
                next($row);
            }
            echo "</tr>";
            /*
            echo $row[0];
            $rnum=count($row);
            for($k=0;$k<$rnum;$k++){
                echo $row[$k]." ";
            }
            echo "<br>";
            */
        }
        echo "</table>";
        /*
        $t=array();
        for($i=0;$i<$num;$i++){
            array_push($t, mysql_result($res,$i));
            $tnum=count($t);
            for($k=0;$k<$tnum;$k++)
            echo $t[$k]." ";
            echo "<br>";
        }
        */
    }
    return $res;
}
function lib_mysql_database_query_form($page,$action,$query){
	
    echo "<form action=\"$page\" method=\"POST\" enctype=\"application/x-www-form-URLencoded\">";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\">";
    echo "<textarea rows=10  cols=120 name=\"query\">";
    $query=str_replace("</textarea>","&lt;/textarea>",$query);
    echo stripslashes($query);
    echo "</textarea><br>";
    echo "<input type=\"submit\" name=\"submit\" value=\"submit query\">";
    echo "</form>";
	
}
function lib_mysql_dump_table($table,$showform,$key,$search,$ignore,$short){
	eval(lib_rfs_get_globals());
	
	if(!empty($ignore)) {
		if(stristr($ignore,",")) {
			$ign=explode(",",$ignore);
		} else {
			$ign=array();
			$ign[0]=$ignore;
		}
	}
	
	
	$fields="*";
	if(stristr($table,",")) { 
		$tbx=explode(",",$table);
		$table=$tbx[0];
		$fields="";
		for($x=1;$x<count($tbx);$x++) {
			$fields.=$tbx[$x].",";
		}
		$fields=rtrim($fields,",");
	}	
    if(stristr($showform, $RFS_SITE_DELIMITER)) {
            $gx=explode($RFS_SITE_DELIMITER,$showform);
            $showform=$gx[0];
    }
    $page=$RFS_SITE_URL.lib_domain_phpself();
    $gt=0;
    $res=lib_mysql_query("select $fields from `$table` $search");
    
    echo "<table border=0 cellpadding=5>";
    $hdr=0;
    while($row=mysql_fetch_assoc($res)){
        $gt++; if($gt>2) $gt=1;
        if($hdr==0){
            echo "<tr>";
            if($showform=="showform"){
            echo "<th></th>";
            }
            reset($row);
            while(key($row)!==NULL){
				
				$ignore_column=0;
				foreach($ign as $igna => $ignb) {
					if($ignb==key($row)) $ignore_column=1;
				}
				
				if(!$ignore_column) {
					echo "<th>";
					echo key($row);	
					echo "</th>";
				}
                next($row);
            }
            echo "</tr>";
            $hdr=1;
        }
        reset($row);

        echo "<tr>";

        $showform_action="rfs_";

        if(count($gx)) {
            $showform_action=$gx[1];
        }

        if($showform=="showform"){
            echo "<td>";
            while(key($row)!=NULL) {
                if(key($row)==$key){
                    $key_val=current($row);
                }
                next($row);
            }

            
            lib_buttons_make_button("$page?action=".$showform_action."edit_$table&$key=$key_val","Edit");
            lib_buttons_make_button("$page?action=".$showform_action."del_$table&$key=$key_val","Delete");
            

            echo"</td>";
        }
        reset($row);
		
        while(key($row)!==NULL){
			$ignore_column=0;
				foreach($ign as $igna => $ignb) {
					if($ignb==key($row)) $ignore_column=1;
				}
				
				if(!$ignore_column) {
					echo "<td class=rfs_project_table_$gt>";
					$txtout=current($row);
					$txtout=str_replace("<","&lt;",$txtout);
					$txtout=str_replace(">","&gt;",$txtout);
					if($short)
						echo lib_string_truncate($txtout,$short);
					else
						echo $txtout;
					echo "</td>";
				}
            next($row);
        }
        echo "</tr>";
    }
    echo "</table>";

    return $res;

}

function lib_mysql_is_csv_data($a,$b,$c,$d) {
}
/////////////////////////////////////////////////////////////////////////////////////////
// This file can not have any trailing spaces
?>
