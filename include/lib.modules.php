<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
// GET MODULES
function sc_get_modules_array() {
	eval(scg());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR lib.modules.php - sc_get_modules() -> [$dr]");
	while(false!==($entry = readdir($d))) {
        if( ($entry == '.') || ($entry == '..') ) { }
        else {
            if(is_dir($dr."/".$entry)) {
                array_push($modules,$entry);
            }
        }
    }
	closedir($d);
	natcasesort($modules);
	reset($modules);
	return $modules;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_module_register($x) { eval(scg());
	global $RFS_MODULE;
	$RFS_MODULE[$x]=true;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_module_installed($x) { eval(scg());
	global $RFS_MODULE;
	return $RFS_MODULE[$x];
}

function sc_show_registered_modules() {
	echo "<h1>Modules Installed</h1><hr>";
	global $RFS_MODULE;
	asort($RFS_MODULE);
	foreach($RFS_MODULE as $k => $v) {
		echo "$k <br>";
	
	}
	
}
	


/////////////////////////////////////////////////////////////////////////////////////////
function sc_get_modules() { eval(scg());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR lib.modules.php - sc_get_modules() -> [$dr]");
	while(false!==($entry = readdir($d))) {
        if( ($entry == '.') || ($entry == '..') ) { }
        else {
            if(is_dir($dr."/".$entry)) {
                $module="$dr/$entry/lib.$entry.php";
                sc_div($module);
                include($module);
                array_push($modules,$entry);
            }
        }
    }
	closedir($d);
	natcasesort($modules);
	reset($modules);
	return $modules;
}
//////////////////////////////////////////////////////////////////////////////////
// MODULE DRAW		  
function sc_draw_module($location) {
	if(stristr(sc_canonical_url(),"admin/adm.php")) return;
	$r=sc_query("select * from arrangement where location='$location' order by sequence");
	if($r) {
		$n=mysql_num_rows($r);
		for($i=0;$i<$n;$i++) {
			$ar=mysql_fetch_object($r);
			if(function_exists("sc_module_$ar->mini")) {
				eval("sc_module_$ar->mini($ar->num);");
				echo "<hr>";
			}
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
?>