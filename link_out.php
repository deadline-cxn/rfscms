<?
// link out 
include("config/config.php");
include("include/lib.mysql.php");
include("include/lib.domain.php");
$link_out=urldecode($_REQUEST['link']);
$link_out=str_replace("_rfs_colon_",":",$link_out);
$foundlink=false;
$result=lib_mysql_query("select * from link_bin where `link` like '%$link_out%'");
if(mysql_num_rows($result)>0) {
	$foundlink=true;
	$link=mysql_fetch_object($result); $link->clicks=$link->clicks+1;
	lib_mysql_query("update link_bin set `clicks` = '$link->clicks' where `id` = '$link->id'");
}
if(empty($link_out)) $link_out=$site_url;
if($foundlink==false) {
		$time=date("Y-m-d h:i:s");
		//echo $time;
lib_mysql_query("insert into link_bin 		 (`name`,		`link`,			 `sname`,		`clicks`,	`time`, `bumptime`, `category`)
    						    VALUES ('$link_out','http://$link_out','$link_out', '1', 		'$time', '$time', 	'!!!TEMP!!!'); ");

}
echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$link_out\">";
?>
