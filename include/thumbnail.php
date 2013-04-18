<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(array_pop(explode("/",getcwd()))=="include") chdir("..");
include_once("include/lib.genm.php");
$img=str_replace($RFS_SITE_URL."/","",$img);
$img=str_replace($RFS_SITE_PATH."/","",$img);
$imgfile=array_pop(explode("/",$img));
if(empty($h)) { if(empty($w)) $h=96; else $scale=1; }
if(empty($w)) $w=96;
//$cfile="$RFS_SITE_PATH/files/pictures/cache/$imgfile.$w.$h.$scale.png";
//if(file_exists($cfile)) {     // header('Content-Type: image/png');  
  //  include($cfile);    exit(); }
if(!stristr($img,$RFS_SITE_PATH)) $img=$RFS_SITE_PATH."/".$img;
$file=$img;
$image_info = getimagesize($file);
$image_type = $image_info[2];
if($image_type == IMAGETYPE_JPEG ) $image = imagecreatefromjpeg($file);
if($image_type == IMAGETYPE_GIF )  $image = imagecreatefromgif($file);
if($image_type == IMAGETYPE_PNG )  $image = imagecreatefrompng($file);
if($scale!=0) {
	$scale=($w/ImageSX($image))*100;
	$w = ImageSX($image) * $scale/100;
	$h = ImageSY($image) * $scale/100;
}
$new_image = imagecreatetruecolor($w, $h);
imagealphablending($new_image, false);
imagesavealpha($new_image, true);
imagecopyresampled($new_image, $image, 0, 0, 0, 0, $w, $h, ImageSX($image), ImageSY($image));
// imagepng($new_image,$cfile);
header('Content-Type: image/png');
imagepng($new_image);
?>