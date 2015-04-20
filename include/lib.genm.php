<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
include_once("lib.rfs.php");
include_once("lib.debug.php");
include_once("lib.sitevars.php");
/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_SESSION['debug_msgs']))
if($_SESSION['debug_msgs']) { lib_genmlog(lib_debug_footer('1')); }
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genmlog($x){ eval(lib_rfs_get_globals());
	if($_SESSION['debug_msgs']){
		$fp=@fopen("$RFS_SITE_PATH/log/lib_genm.log","at");
		if($fp) @fwrite($fp,"$x\n\r");
		@fclose($fp);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_imgtype($x){
	$image_info = getimagesize($x);
	return $image_info[2];
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_img_out($x) {
	header('Content-Type: image/png');
	imagepng($x);
	return;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_imgload($x){
    $y=lib_genm_imgtype($x);
    if($y==IMAGETYPE_XBM)  $iz = imagecreatefromxbm($x);
    if($y==IMAGETYPE_BMP)  $iz = imagecreatefromwbmp($x);
    if($y==IMAGETYPE_ICO)  $iz = imagecreatefromwbmp($x);
    if($y==IMAGETYPE_JPEG) $iz = imagecreatefromjpeg($x);
    if($y==IMAGETYPE_GIF ) $iz = imagecreatefromgif($x);
    if($y==IMAGETYPE_PNG ) $iz = imagecreatefrompng($x);
/*  imagecreatefromgd2
    imagecreatefromgd2part
    imagecreatefromgd
    imagecreatefromstring
    imagecreatefromwbmp
    imagecreatefromxbm
    imagecreatefromxpm
    imagecreatetruecolor  */
	return $iz;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_newimg($w,$h){
	if(empty($w)) $w=100;
	if(empty($h)) $h=$w;
	$iz=imagecreatetruecolor($w,$h);
	imagecolortransparent($iz, imagecolorallocate($iz,0,0,0));
	//imagecolorallocatealpha($iz, 0,0,0, 255));
	//imagealphablending($iz, false);
	//imagesavealpha($iz, true);
	return $iz;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_scale($x,$s) {
	 // TODO: LOL
}
function lib_genm_print_rot_outlined($image,$font_size,$ocolor,$color,$font,$text,$start_x,$start_y,$max_width,$rot) {
	for(     $xx = -1; $xx < 2; $xx++) {
        for( $yy = -1; $yy < 2; $yy++) {
            lib_genm_print_rot($image, $font_size, $ocolor, $font, $text, $start_x+$xx, $start_y+$yy, $max_width,$rot);
        }
    }
    lib_genm_print_rot($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width,$rot);
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_print_rot($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width,$rot) {
    $text=urldecode($text);
    $dim=imagettfbbox($font_size, 0, $font, ".");
    $bh=-$dim[7];
    $zxy=$dim[2];
    $zxw=imagesx($image);
    $zxz=floor(imagesx($image)/$zxy);
    //$text=str_replace("."," . ",$text);
    $text=str_replace("_"," ",$text);
    $text=wordwrap($text, $zxz, "\n", true);
    $text=str_replace("\n"," ",$text);
    $words = explode(" ", $text);
    $string = "";
    $b = imagecolorallocate($image, 0,0,0);
    //imagettftext($image, $font_size, 0, -1, 9, $b, $font, " $zxy  $zxw  $zxz $bh");
    //imagettftext($image, $font_size, 0, 0, 10, $color, $font, " $zxy  $zxw  $zxz $bh");
    for($i=0; $i< count($words); $i++) {
        $dim=imagettfbbox($font_size, $rot, $font, $string.$words[$i]." ");
        $bh=-$dim[7];
        if( (floor($dim[2])) > imagesx($image) ) {
            imagettftext($image, $font_size, $rot, $start_x, $start_y, $color, $font, $string);
            $string = "";
            $start_y += ($bh);
        }
        $string .= $words[$i]." ";
        $dim=imagettfbbox($font_size, $rot, $font, $string);
        $bh=-$dim[7];
        if( (floor($dim[2])) > imagesx($image) ) {
            imagettftext($image, $font_size, $rot, $start_x, $start_y, $color, $font, $string);
            $string = "";
            $start_y += ($bh);
        }
	}
    imagettftext(	$image, $font_size, $rot, $start_x, $start_y, $color, $font, $string);
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_print_outlined($image, $font_size, $ocolor, $color, $font, $text, $start_x, $start_y, $max_width) {
    for(     $xx = -1; $xx < 2; $xx++) {
        for( $yy = -1; $yy < 2; $yy++) {
            lib_genm_print($image, $font_size, $ocolor, $font, $text, $start_x+$xx, $start_y+$yy, $max_width);
        }
    }
    lib_genm_print($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width);
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_genm_print($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width) {
    $text=urldecode($text);
    $dim=imagettfbbox($font_size, 0, $font, ".");
    $bh=-$dim[7];
    $zxy=$dim[2];
    $zxw=imagesx($image);
    $zxz=floor(imagesx($image)/$zxy);
    //$text=str_replace("."," . ",$text);
    $text=str_replace("_"," ",$text);
    $text=wordwrap($text, $zxz, "\n", true);
    $text=str_replace("\n"," ",$text);
    $words = explode(" ", $text);
    $string = "";
    $b = imagecolorallocate($image, 0,0,0);
    //imagettftext($image, $font_size, 0, -1, 9, $b, $font, " $zxy  $zxw  $zxz $bh");
    //imagettftext($image, $font_size, 0, 0, 10, $color, $font, " $zxy  $zxw  $zxz $bh");
    for($i=0; $i< count($words); $i++) {
        $dim=imagettfbbox($font_size, 0, $font, $string.$words[$i]." ");
        $bh=-$dim[7];
        if( (floor($dim[2])) > imagesx($image) ) {
            imagettftext($image, $font_size, 0, $start_x, $start_y, $color, $font, $string);
            $string = "";
            $start_y += ($bh);
        }
        $string .= $words[$i]." ";
        $dim=imagettfbbox($font_size, 0, $font, $string);
        $bh=-$dim[7];
        if( (floor($dim[2])) > imagesx($image) ) {
            imagettftext($image, $font_size, 0, $start_x, $start_y, $color, $font, $string);
            $string = "";
            $start_y += ($bh);
        }
	}
    imagettftext(	$image, $font_size, 0, $start_x, $start_y, $color, $font, $string);
}
/* imagefttext  ( $image_b, $text_size-1, 0,					$zx, $zy,					$color,					$font,					$fizont);    imagettftext ( resource $image , float $size , float $angle , 					int $x , int $y , 					int $color ,					string $fontfile ,					string $text ); 					*/
// this file can not have trailing spaces

function lib_genm_imageline( $img,$x,$y,$x2,$y2,$color,$thickness) {
	
	imagesetthickness($img,$thickness);
	imageline($img,$x,$y,$x2,$y2,$color);
}


