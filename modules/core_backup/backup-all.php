<?php
$dt=date("Y.m.d.Hi");

include("backup-html.php");
include("backup-mysql.php");
include("backup-packages.php");
include("backup-etc.php");
include("backup-library.php");

include("backup-parson.php");
include("backup-worley.php");



system("7z a /backup/".$dt.".all.7z /backup/".$dt."* -mx9");


?>
