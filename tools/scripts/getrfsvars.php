<?
$f=file_get_contents("rfsvars.txt");
$out=array();
//$lnx=explode(":",$f);
//for($d=0;$d<count($lnx);$d+=3) {
//    $page=$lnx[0+$d];
//    $line=$lnx[1+$d];
    $ex=explode("$",$f);// $lnx[2+$d]);

    for($i=0;$i<count($ex);$i++) {
	$ex[$i]=str_replace(".","=",$ex[$i]);
	$ex[$i]=str_replace(" ","=",$ex[$i]);
	$ex[$i]=str_replace(",","=",$ex[$i]);
	$ex[$i]=str_replace("!","=",$ex[$i]);
	$ex[$i]=str_replace("/","=",$ex[$i]);
	$ex[$i]=str_replace("\\","=",$ex[$i]);
	$ex[$i]=str_replace(".","=",$ex[$i]);
	$ex[$i]=str_replace(">","=",$ex[$i]);
	$ex[$i]=str_replace("<","=",$ex[$i]);
	$ex[$i]=str_replace("\"","=",$ex[$i]);
	$ex[$i]=str_replace("'","=",$ex[$i]);
	$ex[$i]=str_replace(")","=",$ex[$i]);
	$ex[$i]=str_replace(";","=",$ex[$i]);
	$ex[$i]=str_replace(":","=",$ex[$i]);
	$ex[$i]=str_replace("-","=",$ex[$i]);
	$ex[$i]=str_replace("+","=",$ex[$i]);
	$ex[$i]=str_replace("*","=",$ex[$i]);
	$cl=explode("=",ltrim($ex[$i]));
	if(stristr($cl[0],"RFS"))
		if(!empty($cl[0]))
			$out[$cl[0]]=$cl[0];
    }
//}

foreach ($out as $cls => $val)
	echo "\$$val \n";

?>
