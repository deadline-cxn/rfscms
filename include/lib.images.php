<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////
function sc_getimagecode($url,$width,$height,$alt) {
	$d=  "<img src=$url border=\"0\" title=\"$alt\" alt=\"$alt\" ";
	if(!empty($width))  $d.="width=\"$width\" ";
	if(!empty($height)) $d.="height=\"$height\" ";
	$d.= ">\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function imgn($url,$alt) {
	$d=  "<img src=$url border=\"0\" title=\"$alt\" alt=\"$alt\">\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function imgs($url,$alt,$w,$h) {
	$d=  "<img src=$url border=\"0\" title=\"$alt\" alt=\"$alt\" width=$w height=$h>\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function imgawh($url,$w,$h,$alt,$ourl){
	$d="<a href=\"$ourl\"><img src=\"$url\" border=\"0\" width=\"$w\" height=\"$h\" title=\"$alt\" alt=\"$alt\"></a>\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function imgat($url,$alt,$ourl,$target) {
	$d="<a href=\"$ourl\" target=\"$target\"><img src=\"$url\" border=\"0\" title=\"$alt\" alt=\"$alt\"></a>\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function imga($url,$alt,$ourl,$target) {
	$d="<a href=\"$ourl\"><img src=\"$url\" border=\"0\" title=\"$alt\" alt=\"$alt\"></a>\n";
	return $d;
}
/////////////////////////////////////////////////////////////////////////
function sc_html2rgb($color) {
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}
/////////////////////////////////////////////////////////////////////////
function sc_rgb2html($r, $g=-1, $b=-1){
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;
    $r = intval($r); $g = intval($g);
    $b = intval($b);
    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));
    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}
/////////////////////////////////////////////////////////////////////////
function sc_picthumb($zimg,$w,$h,$s) { eval(scg());      
$r="<img src=\"$RFS_SITE_URL/include/thumbnail.php/thumb.$zimg?img=$zimg&w=$w&h=$h&scale=$s\">";
return $r;
}
/////////////////////////////////////////////////////////////////////////
function sc_image_text_s($text,$font,$r,$g,$b){
    echo sc_image_text_rt($text,$font,18,222,222,-10,-10,$r,$g,$b,0,0,0,1,0);
}
function sc_image_text_s_rt($text,$font,$r,$g,$b){
    return sc_image_text_rt($text,$font,18,222,222,-10,-10,$r,$g,$b,0,0,0,1,0);
}
/////////////////////////////////////////////////////////////////////////
function sc_image_text( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight) {
echo sc_image_text_rt( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight);
}
/////////////////////////////////////////////////////////////////////////
function sc_image_text_rt( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight) { eval(scg());
$rt="<img src=\"$RFS_SITE_URL/include/generate.image.php/$text.png?action=showfont&font=$font&otext=$text&text_size=$fontsize&owidth=$w&oheight=$h&offx=$ox&offy=$oy&icr=$inicr&icg=$inicg&icb=$inicb&bcr=$inbcr&bcg=$inbcg&bcb=$inbcb&forcerender=$forcerender&forceheight=$forceheight\" border='0' alt='$text' >";
return $rt;
}
/////////////////////////////////////////////////////////////////////////
function sc_percent_bar($percent){ eval(scg());
	echo "<img src=\"$RFS_SITE_URL/include/percentage_bar.php?per=$percent\" alt=\"$percent %\" /> ";
}
?>
