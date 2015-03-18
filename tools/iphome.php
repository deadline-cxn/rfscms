<?php
$fp=fopen("iphome.log","at");
fputs($fp,$_SERVER['REMOTE_ADDR']." ");
$t=date('Y-m-d H:i:s');
fputs($fp,"Last call in:$t\n");
fclose($fp);

?>
