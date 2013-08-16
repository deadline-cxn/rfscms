<? 

if(isset($argv[1])) $inname=$argv[1]; 
else 				$inname=$_REQUEST['inname'];
if(isset($argv[2])) $dtrip=$argv[2];
else $dtrip=$_REQUEST['dtrip'];
////////////////////////////////////////////////////////////////////////
function gT($name){
	$test = strpos($name, "#");
	if($test === FALSE){
		$nameo;
		$trip = $name;
	}
	else{
		$k = explode('#', $name);
		$nameo = $k[0];
		$trip = $k[1];
	}
	if((function_exists('mb_convert_encoding'))){
		mb_substitute_character('none');
		$recoded_cap = mb_convert_encoding($trip, 'Shift_JIS', 'UTF-8');
	}
	$trip = (($recoded_cap != '') ? $recoded_cap : $trip);
	$salt = substr($trip.'H.', 1, 2);
	$salt = preg_replace('/[^\.-z]/', '.', $salt);
	$salt = strtr($salt, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');
	$output = substr(crypt($trip, $salt), -10);
	return $name." ".$output;
	
}

////////////////////////////////////////////////////////////////////////

echo "BASED TRIP CODE GENERATOR\n ";

if(!isset($argv[1])) {
echo "<form><input type=hidden name=aaa value=a>
Enter name<input name=inname value='$inname'>
Desired Trip<input name=dtrip value='$dtrip'> <input type=submit ></form>";
}

$f=0;
while(!$f){
	if(!empty($inname)) {
		$name=$inname."#";
		for($x=0;$x<10;$x++) {
			$name.=chr(rand ( 64, 122));				
		}
		$x=gT($name);
		$y=explode(" ",$x);
		$otrip=$dtrip;
		if(stristr($y[1],$dtrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("a","4",$dtrip);
		if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("i","1",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("A","4",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("I","1",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("e","3",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("s","$",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("g","6",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("t","7",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("s","5",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("o","0",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("i","!",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("o","*",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("h","#",$dtrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }






		$otrip=str_replace("A","4",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("I","1",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("e","3",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("s","$",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("g","6",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("t","7",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("s","5",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("o","0",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("i","!",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
                $otrip=str_replace("l","1",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }
		$otrip=str_replace("h","#",$otrip);
                if(stristr($y[1],$otrip)) { echo $x."\n"; continue; }




	}
}
exit();
?>
