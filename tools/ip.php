<? 
// foreach ($_SERVER as $a => $b ) { echo "$a [$b]<br>"; }
$fp=fopen("ip.txt","wt");
fputs($fp,$_SERVER['REMOTE_ADDR']);
fclose($fp);
?>
