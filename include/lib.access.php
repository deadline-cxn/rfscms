<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////

function lib_access_add_method($func_page,$pact) {
	if(empty($func_page)) return;
	if(empty($pact)) return;	
	lib_mysql_query("delete from `access_methods` where (`page`='$func_page' and `paction`='$pact'); ");
	lib_mysql_query("insert into `access_methods` (`page`,`paction`) VALUES ('$func_page','$pact'); ");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_access_check($func_page,$act){
	if(empty($func_page)) $func_page=lib_domain_phpself();
	$ret=false;
	$d=lib_users_get_data($_SESSION['valid_user']);
    
    if(is_array($d))	
	$ax=$d->access;
    
    if(!empty($ax))    
	if($ax>1) {
		$q="select * from `access` where `access`='$ax' and `page`='$func_page' and `paction`='$act'";
		$r=lib_mysql_query($q);
		if($r->num_rows) $ret=true;	
	}	
	$ax=$d->access_groups;
	$axs=explode(",",$ax);
	for($i=0;$i<count($axs);$i++) {
		if(!empty($axs[$i])) {
			$q="select * from `access` where `name`='$axs[$i]' and `page`='$func_page' and `paction`='$act'";
			$r=lib_mysql_query($q);
			if($r->num_rows) $ret=true;		
		}
	}
	return $ret;
}


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
 