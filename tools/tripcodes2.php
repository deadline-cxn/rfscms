<? 
chdir("..");
include("include/lib.all.php");
if(isset($argv[1])) $inname=$argv[1]; 
else 				$inname=$_REQUEST['inname'];
if(isset($argv[2])) $dtrip=$argv[2];
else $dtrip=$_REQUEST['dtrip'];
$r=sc_query("select * from tripcodes");
$n=mysql_num_rows($r);
echo "<html><head><title>BASED TRIP CODE SEARCH</title></head><body>";
sc_google_adsense("a");
sc_google_analytics();
echo "<br>BASED TRIP CODE SEARCH ($n tripcodes available)<br>";

if(!isset($argv[1])) {
echo "<form><input type=hidden name=aaa value=a>
Desired Trip<input name=dtrip value='$dtrip'> <input type=submit ></form>";
}
if(isset($dtrip)) {
	sc_db_dumptable("tripcodes","no","word"," where 
		(result like '%$dtrip%') or
	   (word like '%$dtrip%') 
	   order by length(`word`) desc");
}

echo "</body></html>";

?>
