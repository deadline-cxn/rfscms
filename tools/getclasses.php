<?php
$f=file_get_contents("classes.txt");
$ex=explode(" ",$f);
$out=array();
for($i=0;$i<count($ex);$i++) {
	$ex[$i]=str_replace("$","0=",$ex[$i]);
	$ex[$i]=str_replace("'","",$ex[$i]);
	$ex[$i]=str_replace("\\","",$ex[$i]);
	$ex[$i]=str_replace("&","=",$ex[$i]);
	$ex[$i]=str_replace(">","=",$ex[$i]);
	$ex[$i]=str_replace("<","=",$ex[$i]);
	$ex[$i]=str_replace("\"","=",$ex[$i]);
	$cl=explode("=",$ex[$i]);
	if($cl[0]=="class") if(!empty($cl[1])) $out[$cl[1]]=$cl[1];
}
foreach ($out as $cls => $val) echo $val."\n"; 

?>
