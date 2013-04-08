<?

$what=$_REQUEST['what'];
$what=str_replace("http://www.defectiveminds.com","",$what);
$m=$_REQUEST['m'];
$s=$_REQUEST['s'];
$what=$what."?m=$m&s=$s";


$ch = curl_init ("$what"); 
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 
$rawdata=curl_exec ($ch); 
curl_close ($ch); 

$crap=explode(".",$what);
$r=count($crap);
$type=$crap[$r-1];

$fp = fopen("counter.txt","r");
if($fp) { $c=fgets($fp,255); fclose($fp); }
$c=$c+1;
$fp = fopen("counter.txt","w");
if($fp) { fputs($fp,$c); fclose($fp); }


$file="hi$c.jpg";
if($rawdata) {
$fp = fopen( $file,'w'); 
fwrite($fp, $rawdata); 
fclose($fp); 

header("Location: $file");
}

?>





