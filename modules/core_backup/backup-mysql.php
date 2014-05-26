<?
 
$fsql="/backup/".$dt.".mysql.sql";
system("mysqldump -u backup -pbackup --databases trainweb > $fsql");

?>
