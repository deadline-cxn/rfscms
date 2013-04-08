<?

$hi=$_REQUEST['hi'];

$amount=$_REQUEST['amount'];



if($hi=="one")

{

  $what=$_REQUEST['what'];

   $what=str_replace(".jpg","",$what);

    $ch = curl_init ("http://i.thottbot.com/en/Interface/Icons/$what.jpg"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



   if($rawdata) {

    $fp = fopen("en/Interface/Icons/$what.jpg",'w'); 

    fwrite($fp, $rawdata); 

    fclose($fp); 

 }





}



if($hi=="yes")

{

$what=$_REQUEST['what'];

$sf=$_REQUEST['sf'];



for($i=1;$i<$amount;$i++)

{



    if($i<10) $il="0".$i;

    else $il=$i;

    $ch = curl_init ("http://i.thottbot.com/en/Interface/Icons/$what$il$sf.jpg"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



   if($rawdata) {

    $fp = fopen("en/Interface/Icons/$what$il$sf.jpg",'w'); 

    fwrite($fp, $rawdata); 

    fclose($fp); 

 }

}

}



if($hi=="two")

{



for($i=30000;$i<40000;$i++)

{



    $ch = curl_init ("http://i.thottbot.com/ir/$i-1.jpg"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



     if($rawdata) {

     $fp = fopen("ir/$i-1.jpg",'w'); 

     fwrite($fp, $rawdata); 

     fclose($fp); 

           echo "ITEM [$i] <br>";

         }

}









}



/*





    $ch = curl_init ("http://i.thottbot.com/js/geturl.js"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



     if($rawdata) {

     $fp = fopen("js/geturl.js",'w'); 

     fwrite($fp, $rawdata); 

     fclose($fp); 

         }



    $ch = curl_init ("http://.thottbot.com/js/wow.js"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



     if($rawdata) {

     $fp = fopen("js/wow.js",'w'); 

     fwrite($fp, $rawdata); 

     fclose($fp); 

         }



    $ch = curl_init ("http://i.thottbot.com/thott.css"); 

     curl_setopt($ch, CURLOPT_HEADER, 0); 

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

     curl_setopt($ch, CURLOPT_BINARYTRANSFER,1); 

     $rawdata=curl_exec ($ch); 

     curl_close ($ch); 



     if($rawdata) {

     $fp = fopen("thott.css",'w'); 

     fwrite($fp, $rawdata); 

     fclose($fp); 

         }





*/







if($_REQUEST['ten']=="forms")

{

    echo "<form>AddPic One:<input type=hidden name=\"hi\" value=\"one\"><input type=hidden name=ten value=forms><input type=text name=\"what\" size=40><input type=submit value=\"Go\"></form>";

    echo "<form>AddPic Bunch:<input type=hidden name=\"hi\" value=\"yes\"><input type=hidden name=ten value=forms><input type=text name=\"what\" size=40><input type=text name=amount value=\"20\"><input type=submit value=\"Go\"></form>";

    echo "<form>AddPic Bunch with Suffix:<input type=hidden name=\"hi\" value=\"yes\"><input type=hidden name=ten value=forms><input type=text name=\"what\" size=40><input type=text name=\"sf\"><input type=text name=amount value=\"20\"><input type=submit value=\"Go\"></form>";

}





$site="http://www.thottbot.com/";

$add="?jor=jor";

foreach ($_GET as $key => $value){    $add=$add."&$key=$value"; }





$site=$site.$add;

$site=str_replace(" ","+",$site);

$site=str_replace("?jor=jor&","?",$site);

$site=str_replace("?jor=jor","",$site);



//echo "[$site]";



$html=file_get_contents($site);

$html=str_replace("thottbot.com/","defectiveminds.com/thott/",$html);



$html=str_replace("i50.","www.",$html);

$html=str_replace("i51.","www.",$html);

$html=str_replace("i52.","www.",$html);

$html=str_replace("i53.","www.",$html);

$html=str_replace("i54.","www.",$html);

$html=str_replace("i55.","www.",$html);

$html=str_replace("i56.","www.",$html);

$html=str_replace("i57.","www.",$html);

$html=str_replace("i58.","www.",$html);

$html=str_replace("i59.","www.",$html);

$html=str_replace("i65.","www.",$html);

$html=str_replace("i61.","www.",$html);

$html=str_replace("i60.","www.",$html);

$html=str_replace("i62.","www.",$html);

$html=str_replace("i63.","www.",$html);

$html=str_replace("i64.","www.",$html);

$html=str_replace("i65.","www.",$html);

$html=str_replace("i66.","www.",$html);

$html=str_replace("i67.","www.",$html);

$html=str_replace("i68.","www.",$html);

$html=str_replace("i69.","www.",$html);

$html=str_replace("i70.","www.",$html);

$html=str_replace("i71.","www.",$html);

$html=str_replace("i72.","www.",$html);

$html=str_replace("i73.","www.",$html);

$html=str_replace("i74.","www.",$html);

$html=str_replace("i75.","www.",$html);

$html=str_replace("ipaper.","www.",$html);

$html=str_replace("i.","www.",$html);



$html=str_replace("INV_AXE_","INV_Axe_",$html);



$html=str_replace("src='?m","src='http://www.defectiveminds.com/thott/sp.php?what=http://www.thottbot.com/&m",$html);

// $html=str_replace("href='?m","href='http://www.defectiveminds.com/thott/sp.php?what=http://www.thottbot.com/&m",$html);





echo $html;



echo "<br>[<a href=http://www.defectiveminds.com/thott/index.php?ten=forms>forms</a>]"; 



?>











































































































































































































































