<? 
$inname=$_REQUEST['inname'];
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

	if(!empty($output))
	echo "<div style='color: green; float: left;'>$name   </div>";
	echo "<div style='color: black; background-color: red; float: left;'> $output </div>";
	echo "<br style='float: none;'>";
	
}

////////////////////////////////////////////////////////////////////////

echo "<h3>BASED TRIP CODE GENERATOR</h3>";
echo "<form><input type=hidden name=aaa value=a>Enter name<input name=inname value='$inname'><input type=submit ></form>";
for($i=0;$i<30;$i++) {
	if(!empty($inname)) {
		$name=$inname."#";
		for($x=0;$x<10;$x++) {
			$name.=chr(rand ( 64, 122));				
		}
		gT($name);	
	}
}
?>