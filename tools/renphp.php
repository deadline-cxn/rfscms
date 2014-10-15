<?
$dirfiles = array();
$dir=getcwd();

$handle=opendir($dir);
if(!$handle) return 0;
while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
closedir($handle);
reset($dirfiles);

foreach($dirfiles as $k => $v) {
	if(!(is_dir($v))) {
		for($i=0;$i<26;$i++)
			$nname.=chr((rand()%26)+65);
		$nname.=$v;
		echo "$nname, $v\n";
		rename($dir."/".$v, $dir."/".$nname);
		$nname="";
		// echo " $k -- $v [$dir]\n";
	}
}


?>