<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
include_once("lib.div.php");
include_once("lib.rfs.php");
include_once("lib.debug.php");
include_once("lib.sitevars.php");
/////////////////////////////////////////////////////////////////////////////////////////
if($_SESSION['debug_msgs']) { genmlog(sc_debugfooter('1')); }
/////////////////////////////////////////////////////////////////////////////////////////
function genmlog($x){ eval(scg());
	if($_SESSION['debug_msgs']){
		$fp=@fopen("$RFS_SITE_PATH/log/genm.log","at");
		if($fp) @fwrite($fp,"$x\n\r");
		@fclose($fp);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_imgtype($x){
	$image_info = getimagesize($x);
	return $image_info[2];
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_img_out($x) {
	header('Content-Type: image/png');
	imagepng($x);
	return;
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_imgload($x){
    $y=genm_imgtype($x);
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
function genm_newimg($w,$h){
	if(empty($w)) $w=100;
	if(empty($h)) $h=$w;
	$iz=imagecreate($w,$h);
	imagecolortransparent($iz, imagecolorallocatealpha($iz, 0, 0, 0, 127));
	imagealphablending($iz, false);
	imagesavealpha($iz, true);
	return $iz;
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_scale($x,$s) {
	 // TODO: LOL
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_print_outlined($image, $font_size, $ocolor, $color, $font, $text, $start_x, $start_y, $max_width) {
    for(     $xx = -1; $xx < 2; $xx++) {
        for( $yy = -1; $yy < 2; $yy++) {
            genm_print($image, $font_size, $ocolor, $font, $text, $start_x+$xx, $start_y+$yy, $max_width);
        }
    }
    genm_print($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width);
}
/////////////////////////////////////////////////////////////////////////////////////////
function genm_print($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width) {
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

function genm_imageline( $img,$x,$y,$x2,$y2,$color,$thickness) {
	
	imagesetthickness($img,$thickness);
	imageline($img,$x,$y,$x2,$y2,$color);
}
									

?>