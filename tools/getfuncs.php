<?
if(isset($argv[1])) $fn=$argv[1];
else $fn="functions.txt";
$f=file_get_contents($fn);
$f=str_replace("../","",$f);
$ex1=explode("\n",$f);
foreach($ex1 as $k => $v) {
	$ex=explode(":",$v);
	$ex2=explode("{",$ex[2]);
	if($ex2[0][0]=="f") {
		$eff=str_replace("function ","",$ex2[0]);
		echo "$eff ";
		echo "($ex[0]($ex[1]))\n";
	}
}

?>

