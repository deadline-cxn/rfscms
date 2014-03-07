<?
chdir("..");
include("include/lib.all.php");
lib_mysql_add("tripcodes","word","text","not null");
lib_mysql_add("tripcodes","code","text","not null");
lib_mysql_add("tripcodes","result","text","not null");

if(isset($argv[1])) { 
	if($argv[1]=="scrub") {
		lib_mysql_query( " CREATE TABLE `tripcodes2` like `tripcodes`; " );
		lib_mysql_query( " INSERT `tripcodes2` SELECT * FROM `tripcodes` GROUP BY `code`;" );
		lib_mysql_query( " RENAME TABLE `tripcodes`  TO `tripcodes3`; " );
		lib_mysql_query( " RENAME TABLE `tripcodes2` TO `tripcodes`; " );
		lib_mysql_query( " DROP TABLE `tripcodes3`; " );
	}
}
////////////////////////////////////////////////////////////////////////
function gT($trip){
	if((function_exists('mb_convert_encoding'))){
		mb_substitute_character('none');
		$recoded_cap = mb_convert_encoding($trip, 'Shift_JIS', 'UTF-8');
	}
	$trip = (($recoded_cap != '') ? $recoded_cap : $trip);
	$salt = substr($trip.'H.', 1, 2);
	$salt = preg_replace('/[^\.-z]/', '.', $salt);
	$salt = strtr($salt, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');
	$output = substr(crypt($trip, $salt), -10);
	return $output;
}

////////////////////////////////////////////////////////////////////////

function add_trip($word,$code,$trip) {
	$code=addslashes($code);
	lib_mysql_query("insert into tripcodes (`word`, `code`,`result`) values('$word', '$code','$trip');");
	$i=mysql_insert_id();
	$code=stripslashes($code);
	// echo " $i] $trip ($word) $code \n";
}

echo "BASED TRIP CODE GENERATOR\n";

$fp=file_get_contents("tools/tripwords.txt");
//$fp=file_get_contents("/usr/share/dict/words");
$x=explode("\n",$fp); $w=array();
foreach($x as $k => $v) {
	if( ( (strlen($v)>4) && (strlen($v)< 11) ) &&
  		( (preg_match("/[A-Z]/", $v)===0) == true ) ) {
		$w[strtolower($v)]=1;
	}
}
$f=0;
$counter=0;
$bcounter=0;
while(!$f){
	if($counter==100) { $bcounter+=$counter; $counter=0;  echo "$bcounter\n"; }
	$trip="";
	$nt=rand(4,15);
	for($x=0;$x<$nt;$x++) {
		$trip.=chr(rand ( 64, 122));
	}
	$x=gT($trip);
	$badded=0;
	for($gg=0;$gg<6;$gg++) {
		for($gx=4;$gx<strlen($x);$gx++) {
			$gh=strtolower(substr($x,$gg,$gx));
			if(isset($w[$gh])) if($badded==0) {
				add_trip($gh,$trip,$x);				
				$badded=1;
				$counter++;
			}
		}
	
	}
}
exit();
?>
