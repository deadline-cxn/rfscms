<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(array_pop(explode("/",getcwd()))=="include") chdir("..");
include_once("include/session.php");
include_once("include/lib.genm.php");
include_once("include/lib.mysql.php");
if(empty($owidth))  { $owidth=128; }
if($owidth!=512)    $usesmalltext=1;
if($font=="random") {
    $dr=$RFS_SITE_PATH."/files/fonts/";
    $fonts=array(); $d = opendir($dr) or die("Wrong path: $dr");
    while(false!==($entry = readdir($d))){
            if(strstr($entry,".ttf"))
            array_push($fonts,$entry);
        }
    closedir($d);
    $x=rand(1,count($fonts));
    $font=$fonts[$x];
}
if(!empty($font)){
    $font=$RFS_SITE_PATH."/files/fonts/".$font;
    $font=str_replace("fonts/fonts/","fonts/",$font);
}
if(!file_exists($font)) {
    $font="$RFS_SITE_PATH/include/fonts/OCRA.ttf";
}
//////////////////////////////////////////// TEXT ONLY SECTION
if( $action=="showfont") {
	if(empty($text_size))        $text_size=9;
	if(!empty($otext))        $fizont=$otext;
    $gfn=explode("/",$font); $font_name=$gfn[count($gfn)-1];
    if(empty($fizont)) {
        $gfn=explode(".",$font_name);
        array_pop($gfn);
        $fizont=join(".",$gfn);
    }
	$renderfile="$RFS_SITE_PATH/files/pictures/rendered/font.$font_name.png";
	if(!empty($renderfile)){
		if($forcerender!=1) {
			if(file_exists($renderfile)){
				header('Content-Type: image/png');
				readfile($renderfile);
				exit();
			}
		}
	}
	$bbox   = imagettfbbox($text_size, 0, $font, $fizont);
	$w = $bbox[2] - $bbox[6];
	$h = $bbox[3] - $bbox[7];
    if(!$forcewidth) $owidth=$w+5;
    if($forceheight) $h=$oheight;
	else $oheight=$h+15;
	$image_b=genm_newimg($owidth,$oheight);
    if( ($bgr) || ($bgg) || ($bgb) ){
        $bgc=imagecolorallocate($image_b,$bgr,$bgg,$bgb);
        imagefill($image_b,1,1,$bgc);
    }
	$bordercolor  = imagecolorallocate($image_b, $bcr, $bcg, $bcb);
	if(empty($icr)) { $icr=255; $icg=255; $icb=255; }
	$color			= imagecolorallocate($image_b, $icr, $icg, $icb);
	if($hideborder!=true)
	for($x=-1;$x<4;$x++) for($y=-1;$y<4;$y++) {
        $zx = $bbox[0] + ($owidth / 2) - ($bbox[4] / 2) ;
        $zy = $bbox[1] + ($oheight / 2) - ($bbox[5] / 2) ;
        $zx+=$x;
        $zy+=$y;
        $zx+=$offx;
        $zy+=$offy;
		imagettftext($image_b, $text_size-1, 0,
		$zx, $zy,
	   $bordercolor, $font, $fizont);
	}
	$zx = $bbox[0] + ($owidth / 2) - ($bbox[4] / 2) ;
	$zy = $bbox[1] + ($oheight / 2) - ($bbox[5] / 2) ;
    $zx+=$offx;
    $zy+=$offy;
	imagettftext($image_b, $text_size-1, 0, $zx, $zy,   $color, 		$font, $fizont);
	@imagepng($image_b,$renderfile);
	if($SESSION['debug_msgs']){
	$color = imagecolorallocate($image_b, 0,255,0);
	imagettftext($image_b, $text_size-1, 0, $zx, $zy,   $color, 		$font,  $fizont);
}
//////////////////////////////////////////// END OF TEXT ONLY SECTION		
} else
    {
    //////////////////////////////////////////// MERGE PICTURE WITH TEXT
	$mid=$_REQUEST['mid'];
	$meme=mfo1("select * from meme where id='$mid'");
	$pic=mfo1("select * from pictures where id='$meme->basepic'");
	$ptf=$RFS_SITE_PATH."/".$pic->url;
	$px=explode("/",$pic->url);
	$py=explode(".",$px[count($px)-1]);
	$pto        =$RFS_SITE_PATH."/"."files/pictures/rendered/tmp.png";
	$renderfile =$RFS_SITE_PATH."/"."files/pictures/rendered/".$py[0].".".$meme->id.".png";
	if(!empty($renderfile)){
		if($forcerender!=1) {
			if(file_exists($renderfile)){
				header('Content-Type: image/png');
				readfile($renderfile);
				exit();
			}
		}
	}
	$image_in = genm_imgload($ptf);
    $dbzf=@ImageSX($image_in);
    if($dbzf==0) $dbzf=1;
    $scale = ( $owidth/$dbzf) *100;
    $oheight = @ImageSY($image_in) * $scale/100;
	$image_b = @imagecreatetruecolor($owidth,$oheight);
    @imagealphablending($image_b, true);
	@imagesavealpha($image_b, true);
	@imagecopyresampled(
    $image_b,   // dst
    $image_in,  // src
    0, 0,       // dst x , dst y
    0, 0,       // src x , src y
    $owidth, $oheight, // dst w, dst h
	@ImageSX($image_in),@ImageSY($image_in) // src w, src h
    );
    
	$w=$owidth;// ImageSX($image_b);
	$h=$oheight;//ImageSY($image_b);

    imagecolortransparent( $image_b, imagecolorallocatealpha($image_b, 0, 0, 0, 127));
        
	imagealphablending($image_b, false);
	imagesavealpha($image_b, true);
	
	//$trans_layer_overlay = imagecolorallocatealpha($image_b, 220, 220, 220, 127);
	//imagefill($image_b, 0, 0, $trans_layer_overlay);

	$black  = imagecolorallocate($image_b, 0, 0, 0);
	$white  = imagecolorallocate($image_b, 255,255,255);
	$red    = imagecolorallocate($image_b, 255, 0, 0);

	if(empty($meme->text_color)) $meme->text_color="white";
	$clr  = mfo1("select * from colors where name='$meme->text_color'");
	$oclr = imagecolorallocate($image_b, $clr->r, $clr->g, $clr->b);
	if(empty($meme->text_bg_color)) $meme->text_bg_color="black";
	$clr  = mfo1("select * from colors where name='$meme->text_bg_color'");
	$bclr = imagecolorallocate($image_b, $clr->r, $clr->g, $clr->b);
           
    $font_file=str_replace("files/fonts/","",$meme->font);
    $font_file=str_replace("fonts/","",$meme->font);
        
    if(empty($font_file)) $font_file = "impact.ttf";

    $font_file=$RFS_SITE_PATH."/files/fonts/".$font_file;

    if(empty($text_size)) $text_size = $owidth/20;
    if(!$usesmalltext)
        if(!empty($meme->text_size))
            $text_size = $meme->text_size;

    $dout=$w."x".$h." ($text_size) ";

    $dimensions = imagettfbbox($text_size, $angle, $font_file, $meme->texttop);
    $textWidth = max($dimensions[2], $dimensions[4]);
    for($zz=0;$zz<7;$zz++) $dout.=" $zz:".$dimensions[$zz];
    $ttx = intval(($w - $textWidth)/2); $dout.=" ttx:$ttx";
    $tty = abs(abs($dimensions[5]) - abs($dimensions[3]))*2;

    $dimensions = imagettfbbox($text_size, $angle, $font_file, $meme->textbottom);
    $textWidth = max($dimensions[2], $dimensions[4]);
    for($zz=0;$zz<7;$zz++) $dout.=" $zz:".$dimensions[$zz];
    $tbx = intval(($w - $textWidth)/2); $dout.=" tbx:$tbx";
    $tby = $h - ( abs(abs($dimensions[5]) - abs($dimensions[3])))*2;

    imagealphablending($image_b, true);

    for($x=-1;$x<4;$x++) for($y=-1;$y<4;$y++) {
        imagettftext($image_b, $text_size, 0, $ttx+$x, $tty+$y, $bclr, $font_file, $meme->texttop);
        imagettftext($image_b, $text_size, 0, $tbx+$x, $tby+$y, $bclr, $font_file, $meme->textbottom);
    }

    imagettftext(
    $image_b, $text_size, 0,
    $ttx+1, $tty+1,
    $oclr, $font_file,
    $meme->texttop);

    imagettftext(
    $image_b, $text_size, 0,
    $tbx+1, $tby+1,
    $oclr, $font_file,
    $meme->textbottom);
    
    $data=sc_getuserdata($_SESSION['valid_user']);

    if(empty($data->donated)) {
            $renderfile=$renderfile.".nd";
                //for($jj=16;$jj>0;$jj--){$jwat = imagecolorallocate($image_b, $jj*16,$jj*16,$jj*16);                    imagefilledrectangle($image_b,0,$h-$jj,$w,$h,$jwat);}
            if($_SESSION['debug_msgs']!=true) {
                $ad="  Create new captions @ $RFS_SITE_URL/";
                imagestring($image_b, 2, 2, $h-16, $ad, $black);
                imagestring($image_b, 2, 3, $h-16, $ad, $black);
                imagestring($image_b, 2, 4, $h-16, $ad, $black);
                imagestring($image_b, 2, 2, $h-14, $ad, $black);
                imagestring($image_b, 2, 3, $h-14, $ad, $black);
                imagestring($image_b, 2, 4, $h-14, $ad, $black);
                imagestring($image_b, 2, 3, $h-15, $ad, $white);
            }
    }
    if($_SESSION['debug_msgs']==true)
        imagestring($image_b, 2, 3, $h-15, $dout, $red);
}

if($meme->datborder=="true") imagerectangle($image_b, 0, 0, $w-1, $h-1, $black);
@imagepng($image_b,$renderfile);

if(!empty($renderfile)){
	if(file_exists($renderfile)){
        header('Content-Type: image/png');
        readfile($renderfile);
        exit();
	}
}
header('Content-Type: image/png');
imagepng($image_b);
exit();



?>
