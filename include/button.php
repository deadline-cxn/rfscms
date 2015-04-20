<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
$RFS_GEN_IMAGE=true; 
if(@array_pop(explode("/",getcwd()))=="include") chdir("..");
include_once("include/lib.genm.php");
if(empty($im)) $im="images/icons/Info.png"; // $im  = image to write text onto
if(empty($w)) $w=64;  // $w   = width
if(empty($h)) $h=$w;  // $h   = height
if(empty($t)) $t="button.php"; // $t   = text to write
if(empty($x)) $x=0; // $x   = left text
if(empty($y)) $y=54; // $y   = top
if(empty($f)) $f="OCRA.ttf";  // $f   = font to use
if(empty($fs)) $fs=9; // $fs  = font size
if(empty($fcr)) $fcr=255;// $fcr,g,b = font color
if(empty($fcg)) $fcg=255;
if(empty($fcb)) $fcb=255;
if(empty($fbr)) $fbr=0;  // $fbr,g,b = font background color
if(empty($fbg)) $fbg=0;
if(empty($fbb)) $fbb=0;
if(!empty($f)) { $f=$RFS_SITE_PATH."/include/fonts/".$f; }
// TODO: Add actual debug method here for this stuff 
//echo $im."<br>";  echo $w."<br>"; echo $h."<br>"; echo $t."<br>"; echo $x."<br>"; echo $y."<br>"; echo $f."<br>"; echo $fs."<br>";
//echo $fcr."<br>"; echo $fcg."<br>"; echo $fcb."<br>"; echo $fbr."<br>"; echo $fbg."<br>";echo $fbb."<br>";  
//////////////////////////////////////////// MERGE PICTURE WITH TEXT
if(!stristr($im,$RFS_SITE_PATH)) $im=$RFS_SITE_PATH."/".$im; // $im=str_replace($RFS_SITE_PATH."/","",$im);
$image_in = lib_genm_imgload($im);
$dbzf     = @ImageSX($image_in); // $s = ( $w/$dbzf) *100;// $h= @ImageSY($image_in) * $s/100;
$image_b  = @imagecreatetruecolor($w,$h);
@imagealphablending($image_b, false);
@imagesavealpha($image_b, true);
@imagecopyresampled( $image_b, $image_in,0, 0,0, 0,$w, $h, @ImageSX($image_in), @ImageSY($image_in));
@imagecolortransparent( $image_b, imagecolorallocatealpha($image_b, 0, 0, 0, 127));
@imagealphablending($image_b, false);
@imagesavealpha($image_b, true);
$bgc = imagecolorallocate($image_b, $fbr, $fbg, $fbb);
$fgc = imagecolorallocate($image_b, $fcr, $fcg, $fcb);
if(empty($fs)) $fs = $w/20;
imagealphablending($image_b, true);
for($lx=0;$lx<3;$lx++) for($ly=0;$ly<3;$ly++) lib_genm_print($image_b, $fs, $bgc , $f, $t, $lx+$x, $ly+$y, $w);
lib_genm_print($image_b, $fs, $fgc , $f, $t, $x+1, $y+1, $w);
header('Content-Type: image/png');
imagepng($image_b);
 