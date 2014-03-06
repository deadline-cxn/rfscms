<? 
// foreach ($_SERVER as $a => $b ) { echo "$a [$b]<br>"; }
$fp=fopen("ip.txt","wt");
fputs($fp,$_SERVER['REMOTE_ADDR']);
$t=date('Y-m-d i:s');
fputs($fp,"\n<br>\nLast updated:$t\n<br>");
fclose($fp);
?>
