<? $RFS_VERSION="v3.1.4";
$RFS_BUILD="001";
$file=fopen("build.dat","r");
if($file) {
$RFS_BUILD=fgets($file,256);
fclose($file);
}
?>