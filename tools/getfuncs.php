<?
$f=file_get_contents("functions.txt");
$ex1=explode("\n",$f);
foreach($ex1 as $k => $v) {
	$ex=explode(":",$v);
	$ex2=explode("{",$ex[2]);
	if($ex2[0][0]=="f") {
		// echo "=======================================================\n";
		echo "$ex2[0] ";
		echo "($ex[0]($ex[1]))\n";
	}
}

?>

