<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
if(!empty($_REQUEST['textbuttons'])){	$_SESSION['textbuttons']=$_REQUEST['textbuttons']; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_img_button_x($link,$name,$img,$x,$y) {
if(!empty($x)) $w=" width=\"$x\" ";
if(!empty($y)) $h=" height=\"$y\" ";
echo "	
<a href=$link>
<img src=\"$img\"
border=0
alt=\"$name\"
text=\"$name\"
title=\"$name\"
$w
$h
>
</a>";	
}
function sc_img_button($link,$name,$img){
	
	sc_img_button_x($link,$name,$img,"","");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button_l($link,$name){    
    echo sc_makebutton($link,$name);    
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button($link,$name){
	sc_button_l($link,$name);
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button_warn($link,$name){
	sc_makebutton_warntest($link,$name);
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebutton($button_link,$button_name){ eval(scg());

	if( ($_SESSION['textbuttons']=="true") ||  (sc_yes($RFS_SITE_TEXT_BUTTONS))) {
		return "[<a href=\"$button_link\">$button_name</a>] ";
	}
	else
		return "<button
					id=\"button\" 
					style=\"font-size:x-small; min-width: 100px;\"
				onclick=\"window.location='$button_link';\"
				
					class=\"menubutton\"
					role=\"button\"
					aria-disabled=\"false\">
			
			<span class=\"ui-button-text\">$button_name</span>
			
			</button>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebutton_vw($link,$name,$w){
    echo "<button id='button' style='font-size:x-small; width: $w"."px;'
    onclick=\"window.open('$link','_top')\">$name</button>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebutton_warntest($link,$name){
    echo "<button id='button' style='background-color: #ff0000; font-size:x-small;'
    onclick=\"window.open('$link','_top')\">$name</button>";
}


?>