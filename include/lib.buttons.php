<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(!empty($_REQUEST['textbuttons'])){	$_SESSION['textbuttons']=$_REQUEST['textbuttons']; }
/////////////////////////////////////////////////////////////////////////////////////////
function lib_buttons_image_sizeable($link,$name,$img,$x,$y) {
	if(!empty($x)) $w=" width=\"$x\" "; if(!empty($y)) $h=" height=\"$y\" ";
	echo "<a href=$link><img src=\"$img\" border=0 alt=\"$name\" text=\"$name\" title=\"$name\" $w $h ></a>";
}
function lib_buttons_image($link,$name,$img){ lib_buttons_image_sizeable($link,$name,$img,"",""); }
/////////////////////////////////////////////////////////////////////////////////////////
function lib_buttons_make_button($link,$name) { echo lib_buttons_text($link,$name); } 
/////////////////////////////////////////////////////////////////////////////////////////
function lib_buttons_text($button_link,$button_name) { eval(lib_rfs_get_globals());
	if(($_SESSION['textbuttons']=="true") || (lib_rfs_bool_true($RFS_SITE_TEXT_BUTTONS))) {
		
		return "[<a href=\"$button_link\">$button_name</a>] ";
	}
	
	
	else {
	
		return "<button id=\"button\" style=\"font-size:x-small; min-width: 100px;\" onclick=\"window.location='$button_link';\" class=\"menubutton\" role=\"button\" aria-disabled=\"false\"> <span class=\"ui-button-text\">$button_name</span></button>";
	
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_buttons_width_text($link,$name,$w) { return "<button id='button' style='font-size:x-small; width: $w"."px;' onclick=\"window.open('$link','_top')\">$name</button>"; }
/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////
function lib_buttons_warn($link,$name) { echo "<button id='button' style='background-color: #ff0000; font-size:x-small;' onclick=\"window.open('$link','_top')\">$name</button>"; }
/////////////////////////////////////////////////////////////////////////////////////////


