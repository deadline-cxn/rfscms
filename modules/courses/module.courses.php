<?
include_once("include/lib.all.php");

lib_menus_register("Courses","$RFS_SITE_URL/modules/courses/courses.php");

lib_access_add_method("course", "edit");
lib_access_add_method("course", "delete");

lib_mysql_add("courses","name","text","NOT NULL");
lib_mysql_add("courses","description","text","NOT NULL");
lib_mysql_add("courses","image","text","NOT NULL");
lib_mysql_add("courses","available","text","NOT NULL");

lib_mysql_add("course_component_type","name","text","NOT NULL");
lib_mysql_add("course_component_type","image","text","NOT NULL");

lib_mysql_add("course_component","name","text","NOT NULL");
lib_mysql_add("course_component","image","text","NOT NULL");
lib_mysql_add("course_component","parent","text","NOT NULL");
lib_mysql_add("course_component","sequence","text","NOT NULL");
lib_mysql_add("course_component","type","text","NOT NULL COMMENT 'course_component_type'");

// add an admin function to be visible in the admin menu
function adm_action_lib_courses_courses() { eval(lib_rfs_get_globals());
    lib_domain_gotopage("$RFS_SITE_URL/modules/courses/courses.php");
}

function module_course_list($x) { eval(lib_rfs_get_globals());
    echo "<h2>Courses available</h2><hr>";
	
    echo "<div class=\"courses_box\">";
    $result=lib_mysql_query("select * from courses");
    $num=mysql_num_rows($result);
    for($i=0;$i<$num;$i++) {
        $course=mysql_fetch_object($result);
			echo "<div>";
		sc_togglediv_start("course_info$course->id"," COURSE: $course->id $course->name",1);
		lib_buttons_make_button("$RFS_SITE_URL/modules/courses/courses.php?action=run&id=$course->id","Take This Course");
		if(lib_access_check("course","edit")) {
			lib_buttons_make_button("$RFS_SITE_URL/modules/courses/courses.php?action=edit&id=$course->id","Edit Course");
		}
		echo "<div class=\"course_info\">";
		echo $course->description;
		echo "</div>";
		sc_togglediv_end();
		echo "</div>";
    }
    echo "</div>";
}

function module_course_admin() { eval(lib_rfs_get_globals());

	if(lib_access_check("course","edit")) {
		lib_buttons_make_button("$RFS_SITE_URL/modules/courses/courses.php?action=edit_list","Edit Courses");		
	}
	
}

function sc_course_components_list($id) { eval(lib_rfs_get_globals());
	$out="";
	$r=lib_mysql_query("select * from course_component where parent='$id'");
		for($i=0;$i<mysql_num_rows($r);$i++) {
			$component=mysql_fetch_object($r);
			$out.=" [UP] [DOWN] [DELETE] [EDIT]---> $component->name $component->id $component->parent<br>";
	}
	echo $out;
}


function lib_ajax_callback_component_add() {
	eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {		
		$q="insert into `$rfatable` (`$rfafield`,`$rfaikey`) VALUES('$rfaajv','$rfakv')";
		// $q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
		$r=lib_mysql_query($q);
		
		if($r) {
			echo "<img src='$RFS_SITE_URL/images/icons/check.png' border=0 width=16>";
		}
		else   echo "<font style='color:white; background-color:red;'>FAILURE: $q</font>";
	}
	else   echo "<font style='color:white; background-color:red;'>NOT AUTHORIZED</font>";
	exit;
}

?>