<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
lib_div(__FILE__);
function lib_modules_get_array() {
	eval(lib_rfs_get_globals());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR lib.modules.php - lib_modules_array() -> [$dr]");
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
function lib_modules_register($x) { eval(lib_rfs_get_globals());
	global $RFS_MODULE;
	@$RFS_MODULE[$x]=true;
}
function lib_modules_installed($x) { eval(lib_rfs_get_globals());
	global $RFS_MODULE;
	return $RFS_MODULE[$x];
}
function lib_modules_show_registered() {
	echo "<h1>Modules Installed</h1><hr>";
	global $RFS_MODULE;
	asort($RFS_MODULE);
	foreach($RFS_MODULE as $k => $v) {
		echo "[ $k ] ";
	}
	echo "<br>";
}
function lib_modules_array() { eval(lib_rfs_get_globals());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR lib.modules.php - lib_modules_array() -> [$dr]");
	while(false!==($entry = readdir($d))) {
        if( ($entry == '.') || ($entry == '..') ) { }
        else {
            if(is_dir($dr."/".$entry)) {
				lib_modules_register($entry);
                $module="$dr/$entry/module.$entry.php";
                lib_div($module);
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
function lib_modules_draw($location) {
	if(stristr(lib_domain_canonical_url(),"admin/adm.php")) return;
	$r=lib_mysql_query("select * from arrangement where location='$location' order by sequence");
	if($r) {
		$n=mysql_num_rows($r);
		for($i=0;$i<$n;$i++) {
			$ar=mysql_fetch_object($r);
			if(function_exists("module_$ar->mini")) {
				eval("module_$ar->mini($ar->num);");
				echo "<hr>";
			}
		}
	}
}
?>