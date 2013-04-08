<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
if(!empty($_REQUEST['textbuttons'])){	$_SESSION['textbuttons']=$_REQUEST['textbuttons']; }
/////////////////////////////////////////////////////////////////////////////////////////

function sc_img_button($link,$name,$img){
	echo "<a href=$link><img src=$img border=0 alt=\"$name\" text=\"$name\"><br>[$name]</a>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button_l($link,$name){
    sc_makebuttonstart();
    sc_makebutton($link,$name);
    sc_makebuttonend();
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button($link,$name){
	if($_SESSION['textbuttons']=="true") {
		echo "[<a href=$link>$name</a>]<br>";
	}
	else {
		sc_button_l($link,$name);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_button_warn($link,$name){
	sc_makebuttonstart();
    sc_makebutton_warntest($link,$name);
    sc_makebuttonend();
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebuttonstart(){
    echo "<div class='menutop'>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebutton($link,$name){
    // echo "<button id='button' style='font-size:x-small; min-width: 100px;' onclick=\"window.open('$link','_top')\">$name</button>";
    echo "
<button id=\"button\" style=\"font-size:x-small; min-width: 100px;\" onclick=\"window.open('$link','_top')\"
class=\"ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only\"
role=\"button\" aria-disabled=\"false\"><span class=\"ui-button-text\">$name</span> </button>";
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
/////////////////////////////////////////////////////////////////////////////////////////
function sc_makebuttonend(){
    echo "</div>";
}

?>
