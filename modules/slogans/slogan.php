<?
$slogan="Exposing the web that you don't want to accept...";
$result=dm_query("select * from slogans");
$numslogans=mysql_num_rows($result);
$which = rand(0,$numslogans);
for($i=0;$i<$which;$i++) {
    $slog=mysql_fetch_array($result);
}
$slogan=stripslashes($slog['slogan']);
?>

