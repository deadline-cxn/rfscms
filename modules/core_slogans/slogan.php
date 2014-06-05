<?
$slogan="Exposing the web that you don't want to accept...";
$result=dm_query("select * from slogans");
$numslogans=$result->num_rows;
$which = rand(0,$numslogans);
for($i=0;$i<$which;$i++) {
    $slog=$result->fetch_array();
}
$slogan=stripslashes($slog['slogan']);
?>

