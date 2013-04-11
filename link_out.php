<?
// link out 
include("config/config.php");
include("include/lib.mysql.php");
include("include/lib.domain.php");
$link_out=$_REQUEST['link'];
$foundlink=false;
$result=sc_query("select * from link_bin where `link` like '%$link_out%'");
if(mysql_num_rows($result)>0) {
	$foundlink=true;
	$link=mysql_fetch_object($result); $link->clicks=$link->clicks+1;
	sc_query("update link_bin set `clicks` = '$link->clicks' where `id` = '$link->id'");
}
if(empty($link_out)) $link_out=$site_url;
if($foundlink==false) {
		$time=date("Y-m-d h:i:s");
		echo $time;
sc_query("insert into link_bin 		 (`name`,		`link`,			 `sname`,		`clicks`,	`time`, `bumptime`, `category`)
    						    VALUES ('$link_out','http://$link_out','$link_out', '1', 		'$time', '$time', 	'!!!TEMP!!!'); ");

}
echo "<META HTTP-EQUIV=\"refresh\" content=\"3;URL=http://$link_out\">";
?>
