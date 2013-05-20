<?
include_once("include/lib.all.php");

sc_access_method_add("course", "edit");
sc_access_method_add("course", "delete");

sc_database_add("courses","name","text","NOT NULL");
sc_database_add("courses","description","text","NOT NULL");
sc_database_add("courses","image","text","NOT NULL");
sc_database_add("courses","available","text","NOT NULL");

sc_database_add("course_component_type","name","text","NOT NULL");
sc_database_add("course_component_type","image","text","NOT NULL");

sc_database_add("course_component","name","text","NOT NULL");
sc_database_add("course_component","image","text","NOT NULL");
sc_database_add("course_component","type","text","NOT NULL COMMENT 'course_component_type'");

function sc_module_course_list($x) { eval(scg());
    echo "<h2>Courses available</h2><hr>";
    echo "<div class=\"courses_box\">";
    $result=sc_query("select * from courses where available='yes'");
    $num=mysql_num_rows($result);
    for($i=0;$i<$num;$i++) {
        $course=mysql_fetch_object($result);
		sc_togglediv_start("course_info$course->id"," COURSE: $course->id $course->name",1);
		sc_button("$RFS_SITE_URL/modules/courses/courses.php?action=run&id=$course->id","Take This Course");
		if(sc_access_check("course","edit")) {
			sc_button("$RFS_SITE_URL/modules/courses/courses.php?action=edit&id=$course->id","Edit Course");
		}
		echo "<div class=\"course_info\">";
		echo $course->description;
		echo "</div>";
		sc_togglediv_end();
    }
    echo "</div>";
}




?>