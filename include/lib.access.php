<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_ACTION_AJAX_TESTPAGE
/* function adm_action_ajax_testpage() { eval(lib_rfs_get_globals());
	echo "<h3>Ajax test page</h3>";
lib_forms_info("
rfs_ajax(data,size,properties,access,callback)<br>
data = table,key_field,key_field_value,field_to_modify<br>
size = width[,height]<br>
properties = [[type[,]][,nohide][,nolabel]<br>
access = access_type, access_action<br>
callback = [ajaxcallback][,javascript_callback]<br>
",white, green);

rfs_ajax("users,id,$data->id,webpage","","nohide","admin,access","");

// rfs_ajax("users,id,$data->id,alias","","select,categories
	exit();
}
 */
function lib_access_add_method($func_page,$act) {
	lib_mysql_query(" CREATE TABLE IF NOT EXISTS `access_methods` (`id` int(11) NOT NULL AUTO_INCREMENT, `page` text COLLATE utf8_unicode_ci NOT NULL,`action` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ; ");
	$r=lib_mysql_query("select * from `access_methods` where `page`='$func_page' and `action`='$act'");
	if($r->num_rows>0) return;
	lib_mysql_query("insert into `access_methods` (`page`,`action`) VALUES('$func_page','$act'); ");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_access_check($func_page,$act){
	if(empty($func_page)) $func_page=lib_domain_phpself();
	$ret=false;
	$d=lib_users_get_data($_SESSION['valid_user']);	
	$ax=$d->access;
	if($ax>1) {
		$q="select * from `access` where `access`='$ax' and `page`='$func_page' and action='$act'";
		$r=lib_mysql_query($q);
		if($r->num_rows) $ret=true;	
	}	
	$ax=$d->access_groups;
	$axs=explode(",",$ax);
	for($i=0;$i<count($axs);$i++) {
		if(!empty($axs[$i])) {
			$q="select * from `access` where `name`='$axs[$i]' and `page`='$func_page' and action='$act'";
			$r=lib_mysql_query($q);
			if($r->num_rows) $ret=true;		
		}
	}
	return $ret;
}

?>