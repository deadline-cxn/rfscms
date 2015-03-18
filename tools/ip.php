<?php 
$fp=fopen("ip.txt","wt");
fputs($fp,$_SERVER['REMOTE_ADDR']."\n");
$t=date('Y-m-d H:i:s');
fputs($fp,"Last updated:$t\n");
fclose($fp);
?>
