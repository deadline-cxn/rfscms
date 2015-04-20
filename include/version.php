<?php $RFS_VERSION="v3.3.6 beta";
$RFS_BUILD="001";
$file=@fopen("build.dat","r");
if($file) { $RFS_BUILD=fgets($file,256); fclose($file); }
$RFS_FULL_VERSION=$RFS_VERSION." BUILD ".$RFS_BUILD;

