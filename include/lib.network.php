<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function echo_updown($x){
    if($x=="up") {
		echo "<img src='images/icons/net_up.png'>";
        //echo "<font style='color: white; background-color: green;'>UP</font>";
    }
    if($x=="down") {
		echo "<img src='images/icons/net_down.png'>";
        // echo "<font style='color: black; background-color: red;'>DOWN</font>";
    }
    if(empty($x) || $x=="unknown"){
		echo "<img src='images/icons/net_yellow.png'>";
       // echo "<font style='color: black; background-color: yellow;'>???</font>";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function rfs_ping($ip){
   exec("ping -c 1 -w 2 -i .2 $ip",$ippa);
	$ipstat="up";
	for($ig=0;$ig<count($ippa);$ig++) {
		//echo $ippa[$ig]."<br>";
		if(strstr($ippa[$ig],"100% packet")){
			//echo "DOWN <BR>";
			$ipstat="down";
		}
	}
	return $ipstat;
}
/////////////////////////////////////////////////////////////////////////////////////////
function ping($host, $port, $timeout) {
  $tB = microtime(true);
@  $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
  if (!$fP) { return "down"; }
  $tA = microtime(true);
  return round((($tA - $tB) * 1000), 2)." ms";
}
/////////////////////////////////////////////////////////////////////////////////////////
function rfs_show_galaga_status(){
echo "<font style='color: orange; background-color: black;'>INFO: galaga has reported in from ip address: ";
$gal=file_get_contents("temp/galagaip.txt"); echo $gal;
$b=explode(" ",$gal);
$g=$b[0];
// if (file_exists("galagaip.txt")) {  echo ". Last update @ " . date ("F d Y H:i:s. ", filemtime("galagaip.txt")); }
echo "Current status:</font>";
$st= ping($g,"80",2);

if($st=="down") echo "<font style='color: white; background-color: red;'>DOWN</font>";
else            echo "<font style='color: white; background-color: green;'> UP $st</font>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function rfs_valid_ip($ip_addr){
  //first of all the format of the ip address is matched
  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
  {
    //now all the intger values are separated
    $parts=explode(".",$ip_addr);
    //now we need to check each part can range from 0-255
    foreach($parts as $ip_parts)
    {
      if(intval($ip_parts)>255 || intval($ip_parts)<0)
      return false; //if number is not within range of 0-255
    }
    return true;
  }
  else
    return false; //if format of ip address doesn't matches
}

