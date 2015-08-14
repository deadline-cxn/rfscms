<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
$gcdx=explode("/",getcwd());
if(array_pop($gcdx)=="include")	chdir("..");
include_once("include/lib.div.php");
include_once("config/config.php");
include_once("include/session.php");
if(isset($act)) {
	if($act=="select_image_go") {
		include("lib.all.php");
		$npath=$_SESSION['select_image_path']."/".$npath;
		$npath=str_replace($RFS_SITE_PATH,"",$npath);
		echo "Image changed to $npath<BR>";
		echo "<br>$rtnpage , $rtnact, $table, $id, $image_field, $npath <br>";
		$q="update `$table` set `$image_field` = '$npath' where `id`='$id'";
		echo $q;
		lib_mysql_query($q);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$RFS_SITE_URL/$rtnpage?action=$rtnact\">";
		exit();
	}
	if($act=="select_image_chdir") {
		include("lib.all.php");
		lib_images_select($npath, $rtnpage, $rtnact, $table, $id, $image_field);
	}
}
function lib_images_select($npath, $rtnpage, $rtnact, $table, $id, $image_field) { eval(lib_rfs_get_globals());
    if(!stristr($_SESSION['select_image_path'],$RFS_SITE_PATH))
        $_SESSION['select_image_path']=$RFS_SITE_PATH.$_SESSION['select_image_path'];
    if($npath==".."){
        $dx=explode("/",$_SESSION['select_image_path']);
        $dp=array_pop($dx);
        $_SESSION['select_image_path']=join("/",$dx);
        echo $_SESSION['select_image_path']."2<BR>";
    }
    else {
        $dc=$_SESSION['select_image_path']."/".$npath;
        echo $dc."<br>";
        if( (filetype($dc)=="dir") ||
            (filetype($dc)=="link") ) {
            $_SESSION['select_image_path']=$dc;
        }
    }
    $wh=lib_mysql_fetch_one_object("select * from `$table` where id='$id'");
    echo "Select Image for (Table $table id[$id] ($wh->name) field[$image_field])<br>";
    $thispath=$_SESSION['select_image_path'];
    echo "$thispath<br>";
    $dir_count=0;
    $dirfiles = array();
    $handle=opendir($thispath) or die("Unable to open filepath");
    while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
    closedir($handle);
    reset($dirfiles);
    asort($dirfiles);
    while(list ($key, $file) = each ($dirfiles)){
        if($file!=".") {
                $op="$thispath/$file";
                $ot=$_SESSION['select_image_path']."/$file";
                if( (@filetype("$op")=="dir") ||
                    (@filetype("$op")=="link") ) {
                   $out="act=select_image_chdir&rtnpage=$rtnpage&rtnact=$rtnact&id=$id&npath=";
                   $out.=urlencode($file);
                   $out.="&table=$table&image_field=$image_field&spath=$RFS_SITE_PATH";
                   echo "<a href='$RFS_SITE_URL/include/lib.images.php?$out'>
                   <img src='$RFS_SITE_URL/images/icons/Folder.png' width=32>($file)</a>";
                            }
                        }
                    }
                    echo "<hr>";
                    reset($dirfiles);
                    asort($dirfiles);
                    while(list ($key, $file) = each ($dirfiles)){
                    if($file!=".") if($file!="..") {
                            $op="$thispath/$file";
                            if(@filetype("$op")=="file") {

                                $ft = lib_file_getfiletype($op);
                                // echo "$ft<br>";
                if( ($ft=="jpg") || ($ft=="png") || ($ft=="gif") || ($ft=="ico") || ($ft=="bmp") ||($ft=="jpeg") ){
                    $out="act=select_image_go&rtnpage=$rtnpage&rtnact=$rtnact&id=$id&npath=";
                    $out.=urlencode($file);
                    $out.="&table=$table&image_field=$image_field&spath=$RFS_SITE_PATH";
                    echo "<a href='$RFS_SITE_URL/include/lib.images.php?$out'><img src='$RFS_SITE_URL/include/button.php?im=".$_SESSION['select_image_path']."/$file&t=$file&w=96&h=96&y=90&fcr=1&fcg=255&fcb=1' border=0></a>";
                }
            }
        }
    }
    for($xyz=0;$xyz<20;$xyz++) echo "<br>";
}
function lib_images_html2rgb($color) {
    if ($color[0] == '#') $color = substr($color, 1);
    if (strlen($color) == 6)     list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
    elseif (strlen($color) == 3) list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else return false; $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
    return array($r, $g, $b);
}
function lib_images_rgb2html($r, $g=-1, $b=-1){
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
function lib_images_thumb($zimg,$w,$h,$s) {
	eval(lib_rfs_get_globals());
	$r="<img src='$RFS_SITE_URL/include/thumbnail.php/thumb.$zimg?img=$zimg&w=$w&h=$h&scale=$s' class='rfs_thumb'>";
	return $r;
}
function lib_images_thumb_raw($zimg,$w,$h,$s) { eval(lib_rfs_get_globals());
$r="$RFS_SITE_URL/include/thumbnail.php/thumb.$zimg?img=$zimg&w=$w&h=$h&scale=$s";
return $r;
}
function lib_images_text_s($text,$font,$r,$g,$b){
    echo lib_images_text_rt($text,$font,18,222,222,-10,-10,$r,$g,$b,0,0,0,1,0);
}
function lib_images_text_s_rt($text,$font,$r,$g,$b){
    return lib_images_text_rt($text,$font,18,222,222,-10,-10,$r,$g,$b,0,0,0,1,0);
}
function lib_images_text( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight) {
	echo lib_images_text_rt( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight);
}
function lib_images_text_rt( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight) { eval(lib_rfs_get_globals());
	$rt="<img src=\"$RFS_SITE_URL/include/generate.image.php/$text.png?action=showfont&font=$font&otext=$text&text_size=$fontsize&owidth=$w&oheight=$h&offx=$ox&offy=$oy&icr=$inicr&icg=$inicg&icb=$inicb&bcr=$inbcr&bcg=$inbcg&bcb=$inbcb&forcerender=$forcerender&forceheight=$forceheight\" border='0' alt='$text'>";
	return $rt;
}
function lib_images_text_small_raw($text,$font) { eval(lib_rfs_get_globals());
	return "$RFS_SITE_URL/include/generate.image.php/$text.png?action=showfont&font=$font&otext=$text&text_size=16&icr=255&icg=255&icb=255&bcr=0&bcg=0&bcb=0&forcerender=1";
}
function lib_images_percent_bar($percent){ eval(lib_rfs_get_globals());
	echo "<img src=\"$RFS_SITE_URL/include/percentage_bar.php?per=$percent\" alt=\"$percent %\" /> ";
}
function lib_images_webpage_to_png($webpage) { eval(lib_rfs_get_globals());
	$md5=md5($webpage);
	system("wkhtmltopdf '$webpage' $RFS_SITE_PATH/tmp/$md5.pdf");
	system("convert $RFS_SITE_PATH/tmp/$md5.pdf -append $RFS_SITE_PATH/tmp/$md5.png");
	$r="$RFS_SITE_URL/tmp/$md5.png";
	return $r;
}

function lib_images_cache($url) {
	global $RFS_SITE_PATH;
	global $RFS_SITE_URL;
	global $RFS_ADDON_NAME;
	lib_file_touch_dir("$RFS_SITE_PATH/cache");
	$t=lib_string_generate_uid(time());
	
	
	if(!empty($RFS_ADDON_NAME)) 
		$p=$RFS_ADDON_NAME.".".lib_domain_last_url_element($url);
	else
		$p=lib_domain_get_current_pagename().".".lib_domain_last_url_element($url);
	
	$local_path="$RFS_SITE_PATH/cache/$t.$p";
	$local_url ="$RFS_SITE_URL/cache/$t.$p";
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$image = curl_exec($ch);
	curl_close($ch);
	$x=file_put_contents($local_path, $image);	
	if($x) return $local_url;
	return "";
}
