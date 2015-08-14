<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_flash_embed_nosize($swf){
        echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" \n";
        echo "codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,28,0\" >\n";
        echo "<param name=src value=\"$swf\">";
        echo "<param name=quality value=high>\n";
        echo "<param name=bgcolor value=ff9900>\n";
        //echo "<param name=wmode value=opaque>\n";
        echo "<param name=wmode value=transparent>\n";
        echo "<param name=menu value=false>\n";
        echo "<embed src=\"$swf\" ";
        echo "menu=false quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" \n";
        echo "type=\"application/x-shockwave-flash\" bgcolor=000000 wmode=transparent>\n";
        echo "</embed></object>";
}

function lib_flash_embed_color($swf,$bgcolor,$width,$height){
    echo "<table border=0 bgcolor=black><tr><td>";
    echo "<table border=0 bgcolor=$bgcolor><tr><td>";
    lib_flash_embed($swf,$width,$height);
    echo "</td></tr></table>";
    echo "</td></tr></table>";
}

function lib_flash_embed_white($swf,$width,$height){
    echo "<table border=0 bgcolor=black><tr><td>";
    echo "<table border=0 bgcolor=white><tr><td>";
    flash($swf,$width,$height);
    echo "</td></tr></table>";
    echo "</td></tr></table>";
}

function lib_flash_embed($swf,$width,$height){
        echo "<object align=center classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" \n";
        echo "codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,28,0\" width=$width height=$height>\n";
        echo "<param name=src value=\"$swf\">";
        echo "<param name=quality value=high>\n";
        echo "<param name=bgcolor value=ff9900>\n";
        echo "<param name=wmode value=transparent>\n";
        echo "<param name=menu value=false>\n";
        echo "<embed src=\"$swf\" ";
        echo "menu=false quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" \n";
        echo "type=\"application/x-shockwave-flash\" bgcolor=000000 wmode=transparent width=$width height=$height>\n";
        echo "</embed></object>";
}

function lib_flash_embed_align($swf,$width,$height,$align){
    echo "<object align=\"$align\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" \n";
    echo "codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,28,0\" width=$width height=$height>\n";
    echo "<param name=src value=\"$swf\">";
    echo "<param name=quality value=high>\n";
    echo "<param name=bgcolor value=ff9900>\n";
    echo "<param name=wmode value=transparent>\n";
    echo "<param name=menu value=false>\n";
    echo "<embed src=\"$swf\" ";
    echo "menu=false quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" \n";
    echo "type=\"application/x-shockwave-flash\" bgcolor=000000 wmode=transparent width=$width height=$height>\n";
    echo "</embed></object>";
}

function lib_flash_embed_code($swf,$width,$height){

    $d=  "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" \n";
    $d.= "codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,28,0\" width=$width height=$height>\n";
    $d.= "<param name=src value=\"$swf\">";
    $d.= "<param name=quality value=high>\n<param name=bgcolor value=ff9900>\n<param name=wmode value=trasnparent>\n<param name=menu value=false>\n";
    $d.= "<embed src=\"$swf\" ";
    $d.= "menu=false quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" \n";
    $d.= "type=\"application/x-shockwave-flash\" bgcolor=000000 wmode=transparent width=$width height=$height>\n";
    $d.= "</embed></object>";
    return $d;
}
